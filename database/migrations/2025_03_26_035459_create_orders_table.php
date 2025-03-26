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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('brand_id');
            $table->tinyInteger('type')
                ->comment('1 = new purchase, 2 = renewal, 3 = trial, 4 = coupon redemption, 5 = gift, 6 = offline');
            $table->string('stripe_checkout_session_id', 255)
                ->nullable();
            $table->string('stripe_invoice_id', 255)
                ->nullable();
            $table->string('stripe_subscription_id', 255)
                ->nullable();
            $table->text('stripe_checkout_session_client_secret')
                ->nullable();
            $table->text('checkout_url')
                ->nullable();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('plan_id')
                ->comment('The plan ID of the order');
            $table->string('referral_code', 255)
                ->nullable();
            $table->tinyInteger('referral_code_type')
                ->nullable()
                ->comment('1: Promo, 2: Renewal Coupon, 3: Coupon, 4: Sales Code');
            $table->decimal('plan_price', 10);
            $table->decimal('sales_tax', 10)->nullable();
            $table->string('status');
            $table->timestamp('paid_at')->nullable();
            $table->boolean('is_hidden')
                ->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
