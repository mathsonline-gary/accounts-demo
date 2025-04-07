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
            $table->tinyInteger('type_id')
                ->comment('1 = new purchase, 2 = renewal, 3 = trial, 4 = coupon redemption, 5 = gift, 6 = offline');

            // User information
            $table->unsignedBigInteger('creator_id')
                ->nullable()
                ->comment('The ID of the user who created the order.');
            $table->unsignedBigInteger('recipient_id')
                ->nullable()
                ->comment('The user ID of the recipient.');
            $table->string('recipient_email', 255)
                ->comment('The snapshot of the recipient email.');
            $table->string('recipient_first_name', 255)
                ->nullable()
                ->comment('The snapshot of the recipient first name.');
            $table->string('recipient_last_name', 255)
                ->nullable()
                ->comment('The snapshot of the recipient last name.');

            // Billing information
            $table->string('billing_address_line_1', 255)
                ->nullable();
            $table->string('billing_address_line_2', 255)
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

            // Item & amount
            $table->unsignedBigInteger('item_id')
                ->comment('The item ID of the order, i.e. plan ID.');
            $table->decimal('amount_subtotal', 10, 2)
                ->comment('The amount of the order before tax is applied.');
            $table->decimal('amount_tax', 10, 2)
                ->nullable()
                ->comment('The amount of tax applied to the order.');

            // Reference code
            $table->string('reference_code', 255)
                ->nullable();
            $table->tinyInteger('reference_code_type_id')
                ->nullable()
                ->comment('1: Promo code, 2: Renewal coupon code, 3: Coupon code, 4: Offline sales code');
            $table->string('reference_code_validation_error', 255)
                ->nullable()
                ->comment('The reason the reference code is invalid. Null if the reference code is valid or not provided.');

            // Status & payment
            $table->string('status');
            $table->timestamp('paid_at')
                ->nullable();
            $table->string('paid_via')
                ->nullable()
                ->comment('The payment gateway used to pay for the order');
            $table->boolean('is_hidden')
                ->default(false);
            $table->text('source')
                ->nullable()
                ->comment('The source of the order, i.e. the full URL of the web page where the user placed the order.');

            $table->timestamps();
        });

        Schema::create('stripe_checkouts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('stripe_checkout_session_id', 255)
                ->unique()
                ->comment('The ID of the Stripe checkout session');
            $table->text('stripe_checkout_session_client_secret')
                ->comment('The client secret of the Stripe checkout session');
            $table->string('stripe_customer_id', 255)
                ->nullable()
                ->comment('The ID of the Stripe customer created from the checkout session. Null if the checkout session is not for a customer.');
            $table->string('stripe_subscription_id', 255)
                ->nullable()
                ->comment('The ID of the Stripe subscription created from the checkout session. Null if the checkout session is not for a subscription.');
            $table->string('stripe_invoice_id', 255)
                ->nullable()
                ->comment('The ID of the Stripe invoice created from the checkout session.');
            $table->string('status')
                ->comment('The status of the Stripe checkout session, one of "open", "complete", or "expired".');
            $table->string('payment_status')
                ->comment('The payment status of the Stripe checkout session, one of "unpaid", "paid", or "no_payment_required".');
            $table->timestamp('expires_at')
                ->comment('The date and time the Stripe checkout session expires.');
            $table->timestamps();
        });

        Schema::create('stripe_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('stripe_payment_intent_id', 255)
                ->unique()
                ->comment('The ID of the Stripe payment intent.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('stripe_checkouts');
        Schema::dropIfExists('stripe_payments');
    }
};
