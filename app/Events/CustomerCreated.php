<?php

namespace App\Events;

use App\Models\User;

class CustomerCreated extends BaseEvent
{
    /**
     * Create a new event instance.
     */
    public function __construct(
        public readonly User $user
    ) {
        parent::__construct($user->created_at);
    }
}
