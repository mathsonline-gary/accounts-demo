<?php

namespace App\Policies;

use App\Enums\OrderType;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create an order.
     */
    public function create(User $user, OrderType $type): Response
    {
        if ($user->role !== UserRole::CUSTOMER) {
            return Response::deny('Only customers can create orders.');
        }

        if (! in_array($type, [
            OrderType::NEW,
            OrderType::RENEWAL,
            OrderType::COUPON_REDEMPTION,
        ])) {
            return Response::deny('Invalid order type for customer.');
        }

        return Response::allow();
    }
}
