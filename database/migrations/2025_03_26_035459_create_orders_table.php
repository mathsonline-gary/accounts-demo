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
            $table->string('uuid', 255)
                ->unique();
            $table->unsignedBigInteger('brand_id');
            $table->tinyInteger('type')
                ->comment('1 = new purchase, 2 = renewal, 3 = trial, 4 = coupon redemption, 5 = gift, 6 = offline');

            // Stripe
            $table->string('stripe_checkout_session_id', 255)
                ->nullable();
            $table->string('stripe_invoice_id', 255)
                ->nullable();
            $table->string('stripe_subscription_id', 255)
                ->nullable();
            $table->text('stripe_checkout_session_client_secret')
                ->nullable();

            // User information
            $table->unsignedBigInteger('creator_id')
                ->comment('The ID of the user who created the order');
            $table->unsignedBigInteger('recipient_id')
                ->comment('The user ID of the recipient');
            $table->string('recipient_email', 255)
                ->comment('The snapshot of the recipient email');
            $table->string('recipient_first_name', 255)
                ->comment('The snapshot of the recipient first name');
            $table->string('recipient_last_name', 255)
                ->comment('The snapshot of the recipient last name');

            // Billing information
            $table->string('billing_address_1', 255)
                ->nullable();
            $table->string('billing_address_2', 255)
                ->nullable();
            $table->string('billing_city', 255)
                ->nullable();
            $table->string('billing_state', 255)
                ->nullable();
            $table->string('billing_postal_code', 255)
                ->nullable();
            $table->string('billing_country', 255)
                ->nullable();
            $table->string('billing_phone', 255)
                ->nullable();
            $table->string('billing_name', 255)
                ->nullable();
            $table->string('billing_email', 255)
                ->nullable();

            // Item & pricing
            $table->unsignedBigInteger('item_id')
                ->comment('The item ID of the order, i.e. plan ID');
            $table->decimal('item_price', 10, 2);
            $table->decimal('sales_tax', 10, 2)
                ->nullable();

            // Referral code
            $table->string('referral_code', 255)
                ->nullable();
            $table->tinyInteger('referral_code_type')
                ->nullable()
                ->comment('1: Promo, 2: Renewal Coupon, 3: Coupon, 4: Offline Sales Code');
            $table->string('referral_code_validation_error', 255)
                ->nullable()
                ->comment('The reason the referral code is invalid. Null if the referral code is valid or not provided.');

            // Status
            $table->string('status');
            $table->timestamp('paid_at')
                ->nullable();
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
