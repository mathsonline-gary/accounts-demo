<?php

namespace App\Services;

use App\Enums\Brand;
use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\ReferralCodeType;
use App\Exceptions\InvalidOrderItemException;
use App\Exceptions\RecentPaidOrderExistsException;
use App\Models\Campaign;
use App\Models\Order;
use App\Models\Plan;
use App\Models\Promo;
use App\Models\RenewalCoupon;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrderService
{
    /**
     * Create a new order
     *
     * @param array{
     *     type: OrderType,
     *     item_id: int,
     *     creator: User,
     *     referral_code: string|null,
     *     referral_code_type: ReferralCodeType|null,
     * } $data
     *
     * @throws RecentPaidOrderExistsException
     * @throws AuthorizationException
     * @throws InvalidOrderItemException
     */
    public function createOrder(array $data): Order
    {
        $data = Arr::only($data, [
            'type',
            'item_id',
            'creator',
            'referral_code',
            'referral_code_type',
        ]);

        $creator = $data['creator'];

        // Authorize the creator to create an order by order type
        if (! $creator->can('create', [Order::class, $data['type']])) {
            Log::warning('The user is unauthorized to create the order type', [
                'user_id' => $creator->id,
                'user_role' => $creator->role->toString(),
                'order_type' => $data['type']->toString(),
            ]);

            throw new AuthorizationException('You are not authorized to create this type of order');
        }

        $order = new Order([
            'brand_id' => $creator->brand_id,
            'uuid' => Str::uuid()->toString(),
            'type' => $data['type'],
            'creator_id' => $creator->id,
            'recipient_id' => $creator->id,
            'recipient_email' => $creator->email,
            'recipient_first_name' => $creator->first_name,
            'recipient_last_name' => $creator->last_name,
            'item_id' => $data['item_id'],
            'status' => OrderStatus::CREATING,
            'referral_code' => $data['referral_code'],
            'referral_code_type' => $data['referral_code_type'],
        ]);

        $this->validateOrder($order);

        $order->save();

        // TODO: Create Stripe Checkout Session

        Log::info('Order created', ['order' => $order->id]);

        return $order;
    }

    /**
     * Check if the user has a recently been paid for an order within 5 minutes.
     */
    private function hasPurchasedRecently(int $recipientId): bool
    {
        $order = Order::ofStatus(OrderStatus::PAID)
            ->ofRecipientId($recipientId)
            ->orderByDesc('paid_at')
            ->first();

        if ($order === null) {
            return false;
        }

        if ($order->paid_at->diffInMinutes() < 5) {
            return true;
        }

        return false;
    }

    /**
     * Validate the Order
     *
     *
     * @throws InvalidOrderItemException
     * @throws RecentPaidOrderExistsException
     */
    private function validateOrder(Order $order): void
    {
        // Check if the user has a recent paid order
        if (in_array($order->type, [OrderType::NEW, OrderType::RENEWAL])) {
            if ($this->hasPurchasedRecently($order->recipient_id)) {
                Log::warning('The user has a recent purchase', [
                    'user_id' => $order->recipient_id,
                ]);

                throw new RecentPaidOrderExistsException;
            }
        }

        // Validate the referral code
        $referral = null;

        switch ($order->referral_code_type) {
            case ReferralCodeType::PROMO:
                if ($order->type !== OrderType::NEW && $order->type !== OrderType::RENEWAL) {
                    Log::warning('The user attempted to apply a promo code for an invalid order type', [
                        'user_id' => $order->creator_id,
                        'promo_code' => $order->referral_code,
                        'order_type' => $order->type->toString(),
                    ]);

                    $order->referral_code_validation_error = 'not_allowed';

                    break;
                }

                try {
                    $promo = Promo::ofBrand(Brand::from($order->brand_id))
                        ->byCode($order->referral_code)
                        ->firstOrFail();
                } catch (ModelNotFoundException) {
                    Log::warning('A user attempted to create an order with a non-existent promo code', [
                        'user_id' => $order->creator_id,
                        'promo_code' => $order->referral_code,
                        'nonce_code' => $order->referral_nonce,
                    ]);

                    $order->referral_code_validation_error = 'not_found';

                    break;
                }

                if ($promo->isExpired()) {
                    Log::warning('A user attempted to create an order with an expired promo code', [
                        'user_id' => $order->creator_id,
                        'promo_code' => $order->referral_code,
                        'nonce_code' => $order->referral_nonce,
                        'expired_at' => $promo->expires_at,
                    ]);

                    $order->referral_code_validation_error = 'expired';

                    break;
                }

                if (! $promo->isActive()) {
                    Log::warning('A user attempted to create an order with an inactive promo code', [
                        'user_id' => $order->creator_id,
                        'promo_code' => $order->referral_code,
                        'nonce_code' => $order->referral_nonce,
                    ]);

                    $order->referral_code_validation_error = 'not_active';

                    break;
                }

                $referral = $promo;

                break;

            case ReferralCodeType::RENEWAL_COUPON:
                if (empty($order->referral_code)) {
                    Log::warning('A user attempted to create an order with an empty renewal coupon', [
                        'user_id' => $order->creator_id,
                    ]);

                    $order->referral_code_validation_error = 'empty_code';

                    break;
                }

                try {
                    $renewalCoupon = RenewalCoupon::ofBrand(Brand::from($order->brand_id))
                        ->byCode($order->referral_code)
                        ->firstOrFail();
                } catch (ModelNotFoundException) {
                    Log::warning('A user attempted to create an order with a non-existent renewal coupon', [
                        'user_id' => $order->creator_id,
                        'renewal_coupon_code' => $order->referral_code,
                    ]);

                    $order->referral_code_validation_error = 'not_found';

                    break;
                }

                if ($renewalCoupon->isRedeemed()) {
                    Log::warning('A user attempted to create an order with a redeemed renewal coupon', [
                        'user_id' => $order->creator_id,
                        'renewal_coupon_code' => $order->referral_code,
                        'redeemed_at' => $renewalCoupon->redeemed_at,
                        'redeemed_by' => $renewalCoupon->authorized_redeemer_id,
                    ]);

                    $order->referral_code_validation_error = 'redeemed';

                    break;
                }

                if ($renewalCoupon->isExpired()) {
                    Log::warning('A user attempted to create an order with an expired renewal coupon', [
                        'user_id' => $order->creator_id,
                        'renewal_coupon_code' => $order->referral_code,
                        'expires_at' => $renewalCoupon->expires_at,
                    ]);

                    $order->referral_code_validation_error = 'expired';

                    break;
                }

                if ($renewalCoupon->redeemer_id !== $order->recipient_id) {
                    Log::warning('A user attempted to create an order with a renewal coupon that cannot be redeemed by the recipient', [
                        'user_id' => $order->creator_id,
                        'renewal_coupon_code' => $order->referral_code,
                        'expected_redeemer_id' => $renewalCoupon->redeemer_id,
                        'actual_redeemer_id' => $order->recipient_id,
                    ]);

                    $order->referral_code_validation_error = 'invalid_redeemer';

                    break;
                }

                $referral = $renewalCoupon;

                break;

            default:
                break;
        }

        // Validate the plan
        try {
            $plan = Plan::ofBrand(Brand::from($order->brand_id))
                ->findOrFail($order->item_id);
        } catch (ModelNotFoundException) {
            Log::warning('A user attempted to create an order with a non-existent plan', [
                'user_id' => $order->creator_id,
                'plan_id' => $order->item_id,
            ]);

            throw new InvalidOrderItemException('Plan not found');
        }

        if ($plan->isTesting() && ! config('services.mol.testing_plans_enabled')) {
            Log::warning('A user attempted to create an order with a disabled testing plan', [
                'user_id' => $order->creator_id,
                'plan_id' => $order->item_id,
            ]);

            throw new InvalidOrderItemException('Testing plans are not enabled');
        }

        $allowedCampaignIds = array_unique([Campaign::default()->first()?->id, $referral?->campaign_id]);
        $planCampaignIds = $plan->campaigns->pluck('id')->toArray();

        if (array_intersect($allowedCampaignIds, $planCampaignIds) === []) {
            Log::warning('A user attempted to create an order with a plan with disallowed campaigns', [
                'user_id' => $order->creator_id,
                'plan_id' => $order->item_id,
                'allowed_campaign_ids' => $allowedCampaignIds,
                'plan_campaign_ids' => $planCampaignIds,
            ]);

            throw new InvalidOrderItemException('Plan not allowed');
        }

        $order->item_price = $plan->price;
    }
}
