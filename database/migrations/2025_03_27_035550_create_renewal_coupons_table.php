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
        Schema::create('renewal_coupons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('brand_id');
            $table->unsignedBigInteger('campaign_id');
            $table->string('code');
            $table->string('note')->nullable();
            $table->unsignedBigInteger('authorized_redeemer_id')
                ->comment('The ID of the user that is authorized to redeem the coupon');
            $table->timestamp('expires_at')
                ->nullable()
                ->comment('The date and time the renewal coupon will expire. Default is 7 days from the date of creation.');
            $table->timestamp('redeemed_at')
                ->nullable()
                ->comment('The date and time the renewal coupon was redeemed. Null if not redeemed.');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('renewal_coupons');
    }
};
