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
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('brand_id');
            $table->unsignedBigInteger('campaign_id');
            $table->string('code');
            $table->string('description')->nullable();
            $table->timestamp('expires_at')
                ->nullable()
                ->comment('The date and time the promo will expire, null for no expiration');
            $table->integer('redemption_count')
                ->default(0)
                ->comment('The number of times the promo has been redeemed');
            $table->boolean('is_active')
                ->default(true)
                ->comment('Whether the promo is currently active for redemptions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};
