<?php

namespace App\Services;

use App\Enums\Brand;
use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\ReferenceCodeType;
use App\Exceptions\Orders\OrderNotFoundException;
use App\Exceptions\Orders\RecentPaidOrderAlreadyExistsException;
use App\Exceptions\Plans\PlanNotFoundException;
use App\Models\Campaign;
use App\Models\Order;
use App\Models\Plan;
use App\Models\Promo;
use App\Models\RenewalCoupon;
use App\Models\User;
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
     *     brand_id: int,
     *     type_id: int,
     *     item_id: int,
     *     creator_id: int|null,
     *     recipient_email: string,
     *     recipient_first_name: string,
     *     recipient_last_name: string,
     *     reference_code: string|null,
     *     reference_code_type_id: int|null,
     *     source: string|null,
     *     with_relations: string[]|null,
     * } $data
     *
     * @throws PlanNotFoundException
     * @throws RecentPaidOrderAlreadyExistsException
     */
    public function createOrder(array $data): Order
    {
        $data = Arr::only($data, [
            'brand_id',
            'type_id',
            'item_id',
            'creator_id',
            'recipient_email',
            'recipient_first_name',
            'recipient_last_name',
            'reference_code',
            'reference_code_type_id',
            'source',
            'with_relations',
        ]);

        $order = new Order([
            'brand_id' => $data['brand_id'],
            'uuid' => Str::uuid()->toString(),
            'type_id' => $data['type_id'],
            'creator_id' => $data['creator_id'],
            'recipient_email' => $data['recipient_email'],
            'recipient_first_name' => $data['recipient_first_name'],
            'recipient_last_name' => $data['recipient_last_name'],
            'item_id' => $data['item_id'],
            'status' => OrderStatus::PENDING,
            'reference_code' => $data['reference_code'],
            'reference_code_type_id' => $data['reference_code_type_id'],
            'source' => $data['source'],
        ]);

        // Set recipient_id if the creator_id is set.
        if ($data['creator_id'] !== null) {
            if (in_array('creator', $data['with_relations'])) {
                $order->load('creator');
            }

            if (in_array('checkout', $data['with_relations'])) {
                $order->recipient_id = in_array($order->type, [OrderType::NEW, OrderType::RENEWAL, OrderType::TRIAL, OrderType::COUPON_REDEMPTION])
                    ? $data['creator_id']
                    : null;
            }
        }

        // Check if the user has a recent paid order.
        if (in_array($order->type, [OrderType::NEW, OrderType::RENEWAL])) {
            if ($this->hasPurchasedRecently($order->recipient_email)) {
                throw new RecentPaidOrderAlreadyExistsException;
            }
        }

        $this->validateOrder($order);

        $order->save();

        Log::info('Order created', ['order' => $order->id]);

        return $order;
    }

    /**
     * Get an order by its UUID.
     *
     * @param  string  $uuid  The UUID of the order.
     * @param  array{
     *     with_relations: string[]|null,
     * } $options
     */
    public function getOrderByUuid(string $uuid, array $options = []): Order
    {
        $options = Arr::only($options, [
            'with_relations',
        ]);

        try {
            $order = Order::byUuid($uuid)->firstOrFail();

            if (in_array('creator', $options['with_relations'])) {
                $order->load('creator');
            }

            return $order;
        } catch (ModelNotFoundException) {
            throw new OrderNotFoundException;
        }
    }

    /**
     * Check if the user has a recently been paid for an order within 5 minutes.
     */
    private function hasPurchasedRecently(string $recipientEmail): bool
    {
        $order = Order::byStatus(OrderStatus::PAID)
            ->byRecipientEmail($recipientEmail)
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
     * Validate the Order.
     *
     * @param  Order  $order  The order to validate.
     *
     * @throws PlanNotFoundException
     * @throws RecentPaidOrderAlreadyExistsException
     */
    private function validateOrder(Order $order): void
    {
        // Validate the reference code
        $reference = null;

        switch ($order->reference_code_type) {
            case ReferenceCodeType::PROMO:
                if ($order->type !== OrderType::NEW && $order->type !== OrderType::RENEWAL) {
                    Log::warning('Promo code is applied to an invalid order type', [
                        'promo_code' => $order->reference_code,
                        'order_type' => $order->type->toString(),
                    ]);

                    $order->reference_code_validation_error = 'not_allowed';

                    break;
                }

                try {
                    $promo = Promo::ofBrand(Brand::from($order->brand_id))
                        ->byCode($order->reference_code)
                        ->firstOrFail();
                } catch (ModelNotFoundException) {
                    Log::warning('Promo code does not exist', [
                        'promo_code' => $order->reference_code,
                    ]);

                    $order->reference_code_validation_error = 'not_found';

                    break;
                }

                if ($promo->isExpired()) {
                    Log::warning('Promo code is expired', [
                        'promo_code' => $order->reference_code,
                        'expired_at' => $promo->expires_at,
                    ]);

                    $order->reference_code_validation_error = 'expired';

                    break;
                }

                if (! $promo->isActive()) {
                    Log::warning('Promo code is not active', [
                        'promo_code' => $order->reference_code,
                    ]);

                    $order->reference_code_validation_error = 'not_active';

                    break;
                }

                $reference = $promo;

                break;

            case ReferenceCodeType::RENEWAL_COUPON:
                if (empty($order->reference_code)) {
                    Log::warning('Renewal coupon is empty', [
                        'user_id' => $order->creator_id,
                    ]);

                    $order->reference_code_validation_error = 'empty_code';

                    break;
                }

                try {
                    $renewalCoupon = RenewalCoupon::ofBrand(Brand::from($order->brand_id))
                        ->byCode($order->reference_code)
                        ->firstOrFail();
                } catch (ModelNotFoundException) {
                    Log::warning('Renewal coupon does not exist', [
                        'renewal_coupon_code' => $order->reference_code,
                    ]);

                    $order->reference_code_validation_error = 'not_found';

                    break;
                }

                if ($renewalCoupon->isRedeemed()) {
                    Log::warning('Renewal coupon is redeemed', [
                        'renewal_coupon_code' => $order->reference_code,
                        'redeemed_at' => $renewalCoupon->redeemed_at,
                        'redeemed_by' => $renewalCoupon->authorized_redeemer_id,
                    ]);

                    $order->reference_code_validation_error = 'redeemed';

                    break;
                }

                if ($renewalCoupon->isExpired()) {
                    Log::warning('Renewal coupon is expired', [
                        'renewal_coupon_code' => $order->reference_code,
                        'expires_at' => $renewalCoupon->expires_at,
                    ]);

                    $order->reference_code_validation_error = 'expired';

                    break;
                }

                if ($renewalCoupon->redeemer_id !== $order->recipient_id) {
                    Log::warning('Renewal coupon is not redeemable by the recipient', [
                        'renewal_coupon_code' => $order->reference_code,
                        'recipient_email' => $order->recipient_email,
                    ]);

                    $order->reference_code_validation_error = 'invalid_redeemer';

                    break;
                }

                $reference = $renewalCoupon;

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

            throw new PlanNotFoundException('Plan not found');
        }

        if ($plan->isTesting() && ! config('services.mol.testing_plans_enabled')) {
            Log::warning('A user attempted to create an order with a disabled testing plan', [
                'user_id' => $order->creator_id,
                'plan_id' => $order->item_id,
            ]);

            throw new PlanNotFoundException('Testing plan not allowed');
        }

        $allowedCampaignIds = array_unique([Campaign::default()->first()?->id, $reference?->campaign_id]);
        $planCampaignIds = $plan->campaigns->pluck('id')->toArray();

        if (array_intersect($allowedCampaignIds, $planCampaignIds) === []) {
            Log::warning('A user attempted to create an order with a plan with disallowed campaigns', [
                'user_id' => $order->creator_id,
                'plan_id' => $order->item_id,
                'allowed_campaign_ids' => $allowedCampaignIds,
                'plan_campaign_ids' => $planCampaignIds,
            ]);

            throw new PlanNotFoundException('Plan not allowed');
        }

        $order->amount_subtotal = $plan->price;
    }
}
