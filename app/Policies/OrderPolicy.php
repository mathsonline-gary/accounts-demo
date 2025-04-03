<?php

namespace App\Policies;

use App\Enums\OrderType;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create an order.
     */
    public function create(?User $user, OrderType $type): bool
    {
        switch ($type) {
            case OrderType::NEW:
            case OrderType::RENEWAL:
            case OrderType::TRIAL:
            case OrderType::COUPON_REDEMPTION:
            case OrderType::GIFT:
                return $user === null || $user->isCustomer();
            case OrderType::OFFLINE:
                return $user?->isAdmin() ?? false;
            default:
                return false;
        }
    }
}
