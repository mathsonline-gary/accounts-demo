<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

abstract class Event
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The timestamp when the event occurred.
     */
    public readonly Carbon $occurredAt;

    /**
     * Create a new event instance.
     */
    public function __construct(?Carbon $occurredAt = null)
    {
        $this->occurredAt = $occurredAt ?? Carbon::now();
    }
}
