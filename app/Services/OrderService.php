<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Exceptions\InvalidOrderException;
use App\Exceptions\RecentPaidOrderExistsException;
use App\Models\Order;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
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

        // Authorize the creator to create an order by order type
        if (! $data['creator']->can('create', [Order::class, $data['type']])) {
            throw new AuthorizationException('You are not authorized to create this type of order.');
        }

        if (in_array($data['type'], [OrderType::NEW, OrderType::RENEWAL])) {
            if ($this->hasPurchasedRecently($data['creator'])) {
                throw new RecentPaidOrderExistsException;
            }
        }

        // Validate plan and related data
        $plan = $this->validatePlanAndRelatedData($data);

        $order = new Order([
            'brand_id' => $data['creator']->brand_id,
            'uuid' => Str::uuid()->toString(),
            'type' => $data['type'],
            'creator_id' => $data['creator']->id,
            'recipient_id' => $data['creator']->id,
            'recipient_email' => $data['creator']->email,
            'recipient_first_name' => $data['creator']->first_name,
            'recipient_last_name' => $data['creator']->last_name,
            'plan_id' => $plan->id,
            'plan_price' => $plan->price,
            'status' => OrderStatus::CREATING,
        ]);

        $order->save();

        return $order;
    }

    /**
     * Check if the user has a recent paid order within 5 minutes
     */
    private function hasPurchasedRecently(User $user): bool
    {
        $order = Order::ofStatus(OrderStatus::PAID)
            ->ofRecipientId($user->id)
            ->sortByDesc('paid_at')
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
     * Validate plan and related data based on order type
     *
     * @throws InvalidOrderException
     */
    private function validatePlanAndRelatedData(array $data): Plan
    {
        // Get allowed campaign IDs based on order type
        $allowedCampaignIds = $this->getAllowedCampaignIds($data['type']);

        // Get allowed plan IDs based on order type and campaign IDs
        $allowedPlanIds = $this->getAllowedPlanIds($data['type'], $allowedCampaignIds);

        // Validate and find the plan
        try {
            $plan = Plan::findOrFail($data['plan_id']);
        } catch (ModelNotFoundException) {
            throw new InvalidOrderException('Invalid plan');
        }

        // Validate plan is in allowed list
        if (! in_array($plan->id, $allowedPlanIds)) {
            throw new InvalidOrderException('Plan is not available for this order type');
        }

        // Validate renewal coupon for renewal orders
        if ($data['type'] === OrderType::RENEWAL) {
            $this->validateRenewalCoupon($data['renewal_coupon_code']);
        }

        // Validate promo code for new or renewal orders
        if (in_array($data['type'], [OrderType::NEW, OrderType::RENEWAL])) {
            $this->validatePromoCode($data['promo_code'], $plan);
        }

        return $plan;
    }

    /**
     * Get allowed campaign IDs based on order type
     */
    private function getAllowedCampaignIds(OrderType $type): array
    {
        return match ($type) {
            OrderType::NEW => [1, 2, 3], // Example campaign IDs for new orders
            OrderType::RENEWAL => [4, 5, 6], // Example campaign IDs for renewal orders
            OrderType::TRIAL => [7, 8, 9], // Example campaign IDs for trial orders
            OrderType::COUPON_REDEMPTION => [10, 11, 12], // Example campaign IDs for coupon redemption
            OrderType::GIFT => [13, 14, 15], // Example campaign IDs for gift orders
            OrderType::OFFLINE => [16, 17, 18], // Example campaign IDs for offline orders
        };
    }

    /**
     * Get allowed plan IDs based on order type and campaign IDs
     */
    private function getAllowedPlanIds(OrderType $type, array $campaignIds): array
    {
        return Plan::query()
            ->whereIn('campaign_id', $campaignIds)
            ->where('type', $type)
            ->pluck('id')
            ->toArray();
    }

    /**
     * Validate renewal coupon
     *
     * @throws InvalidOrderException
     */
    private function validateRenewalCoupon(?string $code): void
    {
        if (empty($code)) {
            throw new InvalidOrderException('Renewal coupon code is required for renewal orders');
        }

        // TODO: Add actual renewal coupon validation logic
        // Example:
        // $coupon = RenewalCoupon::where('code', $code)->first();
        // if (!$coupon || !$coupon->isValid()) {
        //     throw new InvalidOrderException('Invalid or expired renewal coupon');
        // }
    }

    /**
     * Validate promo code
     *
     * @throws InvalidOrderException
     */
    private function validatePromoCode(?string $code, Plan $plan): void
    {
        if (empty($code)) {
            return; // Promo code is optional
        }

        // TODO: Add actual promo code validation logic
        // Example:
        // $promo = Promo::where('code', $code)->first();
        // if (!$promo || !$promo->isValidForPlan($plan)) {
        //     throw new InvalidOrderException('Invalid or expired promo code');
        // }
    }
}
