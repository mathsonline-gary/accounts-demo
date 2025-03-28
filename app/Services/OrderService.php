<?php

namespace App\Services;

use App\Enums\Brand;
use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\ReferralCodeType;
use App\Exceptions\InvalidNonceCodeException;
use App\Exceptions\InvalidOrderException;
use App\Exceptions\PlanNotFoundException;
use App\Exceptions\PromoExpiredException;
use App\Exceptions\PromoNotActiveException;
use App\Exceptions\PromoNotFoundException;
use App\Exceptions\RecentPaidOrderExistsException;
use App\Exceptions\RenewalCouponExpiredException;
use App\Exceptions\RenewalCouponNotFoundException;
use App\Exceptions\RenewalCouponRedeemedException;
use App\Models\Campaign;
use App\Models\Order;
use App\Models\Plan;
use App\Models\Promo;
use App\Models\RenewalCoupon;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
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
     *     plan_id: int,
     *     creator: User,
     *     renewal_coupon_code: string|null,
     *     promo_code: string|null,
     *     nonce_code: string|null,
     * } $data
     *
     * @throws RecentPaidOrderExistsException
     * @throws AuthorizationException
     * @throws InvalidOrderException
     */
    public function createOrder(array $data): Order
    {
        $data = Arr::only($data, [
            'type',
            'plan_id',
            'creator',
            'renewal_coupon_code',
            'promo_code',
            'nonce_code',
        ]);

        $creator = $data['creator'];
        $brand = Brand::from($creator->brand_id);
        $appliedPromoCode = $data['promo_code'];
        $appliedRenewalCouponCode = $data['renewal_coupon_code'];

        // Authorize the creator to create an order by order type
        if (! $creator->can('create', [Order::class, $data['type']])) {
            Log::warning('An unauthorized user attempted to create an order', [
                'user_id' => $creator->id,
                'user_role' => $creator->role->toString(),
                'order_type' => $data['type']->toString(),
            ]);

            throw new AuthorizationException('You are not authorized to create this type of order');
        }

        // Check if the user has a recent paid order
        if (in_array($data['type'], [OrderType::NEW, OrderType::RENEWAL])) {
            if ($this->hasPurchasedRecently($data['creator'])) {
                Log::warning('A user attempted to create an order while having a recent purchase', [
                    'user_id' => $creator->id,
                ]);

                throw new RecentPaidOrderExistsException;
            }
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
            'status' => OrderStatus::CREATING,
        ]);

        // Validate and get promo if provided
        $promo = null;

        try {
            $promo = $this->getValidPromo($brand, $appliedPromoCode, $data['nonce_code']);
        } catch (InvalidNonceCodeException) {
            Log::warning('A user attempted to create an order with an invalid nonce code', [
                'user_id' => $creator->id,
                'promo_code' => $appliedPromoCode,
                'nonce_code' => $data['nonce_code'],
            ]);

            $order->referral_code_validation_error = 'invalid_nonce';
        } catch (PromoNotFoundException) {
            Log::warning('A user attempted to create an order with a non-existent promo code', [
                'user_id' => $creator->id,
                'promo_code' => $appliedPromoCode,
                'nonce_code' => $data['nonce_code'],
            ]);

            $order->referral_code_validation_error = 'not_found';
        } catch (PromoExpiredException) {
            Log::warning('A user attempted to create an order with an expired promo code', [
                'user_id' => $creator->id,
                'promo_code' => $appliedPromoCode,
                'nonce_code' => $data['nonce_code'],
            ]);

            $order->referral_code_validation_error = 'expired';
        } catch (PromoNotActiveException) {
            Log::warning('A user attempted to create an order with an inactive promo code', [
                'user_id' => $creator->id,
                'promo_code' => $appliedPromoCode,
                'nonce_code' => $data['nonce_code'],
            ]);

            $order->referral_code_validation_error = 'not_active';
        } finally {
            if (! empty($appliedPromoCode)) {
                $order->referral_code = $appliedPromoCode;
                $order->referral_code_type = ReferralCodeType::PROMO;
            }
        }

        // Validate and get renewal coupon if provided
        $renewalCoupon = null;

        try {
            $renewalCoupon = $this->getValidRenewalCoupon($brand, $appliedRenewalCouponCode);
        } catch (RenewalCouponNotFoundException) {
            Log::warning('A user attempted to create an order with a non-existent renewal coupon', [
                'user_id' => $creator->id,
                'renewal_coupon_code' => $appliedRenewalCouponCode,
            ]);

            $order->referral_code_validation_error = 'not_found';
        } catch (RenewalCouponExpiredException) {
            Log::warning('A user attempted to create an order with an expired renewal coupon', [
                'user_id' => $creator->id,
                'renewal_coupon_code' => $appliedRenewalCouponCode,
            ]);

            $order->referral_code_validation_error = 'expired';
        } catch (RenewalCouponRedeemedException) {
            Log::warning('A user attempted to create an order with a redeemed renewal coupon', [
                'user_id' => $creator->id,
                'renewal_coupon_code' => $appliedRenewalCouponCode,
            ]);

            $order->referral_code_validation_error = 'redeemed';
        } finally {
            if (! empty($appliedRenewalCouponCode)) {
                $order->referral_code = $appliedRenewalCouponCode;
                $order->referral_code_type = ReferralCodeType::RENEWAL_COUPON;
            }
        }

        // Validate and get plan
        try {
            $plan = $this->getValidPlan($brand, $data['plan_id'], $promo, $renewalCoupon);
            $order->plan_id = $plan->id;
            $order->plan_price = $plan->price;
        } catch (PlanNotFoundException) {
            Log::warning('A user attempted to create an order with an invalid plan', [
                'user_id' => $creator->id,
                'plan_id' => $data['plan_id'],
                'promo_code' => $appliedPromoCode,
                'renewal_coupon_code' => $appliedRenewalCouponCode,
            ]);

            throw new InvalidOrderException('Invalid plan');
        }

        $order->save();

        Log::info('Order created', ['order' => $order->id]);

        // TODO: Create Stripe Checkout Session

        return $order;
    }

    /**
     * Check if the user has a recent paid order within 5 minutes
     */
    private function hasPurchasedRecently(User $user): bool
    {
        $order = Order::ofStatus(OrderStatus::PAID)
            ->ofRecipientId($user->id)
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
     * Get a valid promo code
     *
     * @throws PromoNotFoundException
     * @throws PromoExpiredException
     * @throws PromoNotActiveException
     */
    private function getValidPromo(Brand $brand, ?string $promoCode, ?string $nonceCode): ?Promo
    {
        if (empty($promoCode)) {
            return null;
        }

        if ($promoCode === 'ORIG') {
            if (empty($nonceCode) || ! verify_nonce_code($nonceCode)) {
                throw new InvalidNonceCodeException;
            }
        }

        $promo = Promo::ofBrand($brand)->byCode($promoCode)->first();

        if ($promo === null) {
            throw new PromoNotFoundException;
        }

        if ($promo->isExpired()) {
            throw new PromoExpiredException;
        }

        if (! $promo->isActive()) {
            throw new PromoNotActiveException;
        }

        return $promo;
    }

    /**
     * Get a valid renewal coupon
     *
     * @throws RenewalCouponNotFoundException
     * @throws RenewalCouponExpiredException
     * @throws RenewalCouponRedeemedException
     */
    private function getValidRenewalCoupon(Brand $brand, ?string $renewalCouponCode): ?RenewalCoupon
    {
        if (empty($renewalCouponCode)) {
            return null;
        }

        $renewalCoupon = RenewalCoupon::ofBrand($brand)->byCode($renewalCouponCode)->first();

        if ($renewalCoupon === null) {
            throw new RenewalCouponNotFoundException;
        }

        if ($renewalCoupon->isRedeemed()) {
            throw new RenewalCouponRedeemedException;
        }

        if ($renewalCoupon->isExpired()) {
            throw new RenewalCouponExpiredException;
        }

        return $renewalCoupon;
    }

    /**
     * Get a valid plan
     *
     * @throws PlanNotFoundException
     */
    private function getValidPlan(Brand $brand, int $planId, ?Promo $promo, ?RenewalCoupon $renewalCoupon): Plan
    {
        $allowedCampaignIds = [Campaign::default()->first()->id];

        if ($promo !== null) {
            $allowedCampaignIds[] = $promo->campaign_id;
        }

        if ($renewalCoupon !== null) {
            $allowedCampaignIds[] = $renewalCoupon->campaign_id;
        }

        $allowedCampaignIds = array_unique($allowedCampaignIds);

        $allowedPlans = Plan::ofBrand($brand)->inCampaignIds($allowedCampaignIds)->get();

        if (config('services.mol.testing_plans_enabled')) {
            $testingPlans = Plan::ofBrand($brand)->testing()->get();
            $allowedPlans = $allowedPlans->merge($testingPlans);
        }

        if ($allowedPlans->isEmpty()) {
            throw new PlanNotFoundException;
        }

        $plan = $allowedPlans->firstWhere('id', $planId);

        if ($plan === null) {
            throw new PlanNotFoundException;
        }

        return $plan;
    }
}
