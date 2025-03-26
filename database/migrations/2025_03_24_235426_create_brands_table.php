<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->char('hash', 64)->unique();
            $table->string('country_name');
            $table->char('country_code', 2);
            $table->string('domain');
            $table->string('marketing_website_url');
            $table->string('noreply_email');
            $table->string('noreply_name');
            $table->string('support_email');
            $table->string('support_name');
            $table->string('feedback_email');
            $table->string('feedback_name');
            $table->string('support_phone');
            $table->string('social_media_facebook')->nullable();
            $table->string('social_media_instagram')->nullable();
            $table->string('social_media_linkedin')->nullable();
            $table->string('stripe_publishable_key')->nullable();
            $table->string('stripe_secret_key')->nullable();
            $table->string('stripe_webhook_secret')->nullable();
            $table->string('keap_account_key')->nullable();

            // Indexes
            $table->index('hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
