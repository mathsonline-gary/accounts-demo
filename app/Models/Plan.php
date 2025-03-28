<?php

namespace App\Models;

use App\Enums\Brand;
use App\Enums\PlanType;
use App\ValueObjects\BillingPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Plan extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'brand_id',
        'stripe_price_id',
        'code',
        'description',
        'price_original',
        'price',
        'price_saved',
        'is_recurring',
        'period_in_months',
        'extra_months',
        'student_limit',
        'type',
        'currency',
    ];

    public function casts(): array
    {
        return [
            'type' => PlanType::class,
            'is_recurring' => 'boolean',
            'price' => 'float',
            'price_original' => 'float',
            'price_saved' => 'float',
            'period_in_months' => 'integer',
            'extra_months' => 'integer',
            'student_limit' => 'integer',
        ];
    }

    /**
     * Interact with the plan's billing period.
     */
    public function billingPeriod(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => new BillingPeriod(
                count: $attributes['period_in_months'] - $attributes['extra_months'],
                interval: BillingPeriod::INTERVAL_MONTH,
                extraCount: $attributes['extra_months'],
            )
        );
    }

    /**
     * Get the campaigns associated with the plan.
     */
    public function campaigns(): BelongsToMany
    {
        return $this->belongsToMany(Campaign::class, 'campaign_plan', 'plan_id', 'campaign_id');
    }

    /**
     * Scope a query to only include plans of a given brand.
     */
    public function scopeOfBrand(Builder $query, Brand $brand): void
    {
        $query->where('brand_id', $brand->value);
    }

    /**
     * Scope a query to only include plans of a given type.
     */
    public function scopeOfType(Builder $query, PlanType $type): void
    {
        $query->where('type', $type);
    }

     /**
     * Scope a query to only include testing plans.
     */
    public function scopeTesting(Builder $query): void
    {
        $query->where('price', 1);
    }

    /**
     * Scope a query to only include plans that are recurring or not.
     */
    public function scopeIsRecurring(Builder $query, bool $isRecurring): void
    {
        $query->where('is_recurring', $isRecurring);
    }

    /**
     * Scope a query to only include plans that are hidden or not.
     */
    public function scopeIsHidden(Builder $query, bool $isHidden): void
    {
        $query->where('is_hidden', $isHidden);
    }

    /**
     * Scope a query to only include plans that are associated with the given campaign IDs.
     */
    public function scopeInCampaignIds(Builder $query, array $campaignIds): void
    {
        $query->whereHas('campaigns', function (Builder $query) use ($campaignIds) {
            $query->whereIn('campaign_id', $campaignIds);
        });
    }

    /**
     * Scope a query to only include plans that have the given period in months.
     */
    public function scopeOfPeriodInMonths(Builder $query, int $periodInMonths): void
    {
        $query->where('period_in_months', $periodInMonths);
    }

    /**
     * Scope a query to only include plans that have the given student limit.
     */
    public function scopeOfStudentLimit(Builder $query, int $studentLimit): void
    {
        $query->where('student_limit', $studentLimit);
    }

    /**
     * Scope a query to only include plans that are single student plans.
     */
    public function scopeForSingleStudent(Builder $query): void
    {
        $query->where('student_limit', 1);
    }

    /**
     * Scope a query to only include plans that are family plans.
     */
    public function scopeForFamily(Builder $query): void
    {
        $query->where('student_limit', '>', 1);
    }
    
    /**
     * Determine if the plan is recurring.
     */
    public function isRecurring(): bool
    {
        return $this->is_recurring;
    }

    /**
     * Determine if the plan is for standard customers.
     */
    public function isTypeStandard(): bool
    {
        return $this->type === PlanType::STANDARD;
    }

    /**
     * Determine if the plan is for standard customers.
     */
    public function isTypeHomeschool(): bool
    {
        return $this->type === PlanType::HOMESCHOOL;
    }
    
}
