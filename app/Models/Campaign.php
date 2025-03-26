<?php

namespace App\Models;

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
}
