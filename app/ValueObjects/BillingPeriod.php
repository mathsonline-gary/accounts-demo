<?php

namespace App\ValueObjects;

class BillingPeriod
{
    public const INTERVAL_DAY = 'day';

    public const INTERVAL_WEEK = 'week';

    public const INTERVAL_MONTH = 'month';

    public const INTERVAL_YEAR = 'year';

    /**
     * @var int The number of intervals in the billing period.
     */
    public int $count;

    /**
     * @var int The number of extra intervals in the billing period.
     */
    public int $extraCount;

    /**
     * @var string The interval of the billing period. One of the INTERVAL_* constants.
     */
    public string $interval;

    /**
     * Construct a new Recurrence instance.
     */
    public function __construct(int $count, string $interval = self::INTERVAL_MONTH, int $extraCount = 0)
    {
        $this->count = $count;
        $this->interval = $interval;
        $this->extraCount = $extraCount;
    }

    /**
     * Get the string representation of the billing period.
     * For example, "12 months" or "2 weeks".
     */
    public function toString(): string
    {
        $total = $this->count + $this->extraCount;

        if ($total > 1) {
            return "{$total} {$this->interval}s";
        }

        return "{$total} {$this->interval}";
    }
}
