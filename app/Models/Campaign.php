<?php

namespace App\Models;

use App\Enums\CampaignCode;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'code',
        'description',
        'tags',
    ];

    public function casts(): array
    {
        return [
            'tags' => 'array',
        ];
    }

    /**
     * Scope a query to get the default campaign.
     */
    public function scopeDefault(Builder $query): void
    {
        $query->where('code', CampaignCode::REGULAR);
    }
}
