<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'hash',
        'country_name',
        'country_code',
        'domain',
        'marketing_website_url',
        'teacher_website_url',
        'student_website_url',
        'admin_website_url',
        'noreply_email',
        'noreply_name',
        'support_email',
        'support_name',
        'feedback_email',
        'feedback_name',
        'support_phone',
        'social_media_facebook',
        'social_media_instagram',
        'social_media_linkedin',
        'stripe_publishable_key',
        'stripe_secret_key',
        'stripe_webhook_secret',
        'keap_account_key',
    ];
}
