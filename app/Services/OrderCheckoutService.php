<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\OrderCheckouts\OrderCheckoutAlreadyExistsException;
use App\Exceptions\OrderCheckouts\OrderCheckoutNotCreatedException;
use App\Exceptions\Orders\OrderAlreadyCancelledException;
use App\Exceptions\Orders\OrderAlreadyExpiredException;
use App\Exceptions\Orders\OrderAlreadyPaidException;
use App\Exceptions\Orders\OrderNotFoundException;
use App\Models\Order;
use App\Models\StripeCheckout;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class OrderCheckoutService
{
    /**
     * Create a Stripe checkout session for the order.
     *
     * @param array{
     *     order_uuid: string,
     *     url_on_completion: string,
     * } $data
     *
     * @throws OrderCheckoutNotCreatedException
     * @throws OrderNotFoundException
     * @throws OrderAlreadyPaidException
     * @throws OrderAlreadyCancelledException
     * @throws OrderAlreadyExpiredException
     * @throws OrderCheckoutAlreadyExistsException
     */
    public function createCheckout(array $data): StripeCheckout
    {
        $data = Arr::only($data, [
            'order_uuid',
            'url_on_completion',
        ]);

        try {
            $order = Order::byUuid($data['order_uuid'])->firstOrFail();
        } catch (ModelNotFoundException) {
            throw new OrderNotFoundException;
        }

        // Validate the order status.
        match (true) {
            $order->isStatusPending() => null,
            $order->isStatusPaid() => throw new OrderAlreadyPaidException,
            $order->isStatusCancelled() => throw new OrderAlreadyCancelledException,
            $order->isStatusExpired() => throw new OrderAlreadyExpiredException,
        };

        if ($order->stripeCheckout()->exists()) {
            throw new OrderCheckoutAlreadyExistsException;
        }

        $stripe = new StripeClient($order->brand->stripe_secret_key);

        try {
            $session = $this->createStripeCheckoutSession($order, $stripe, $data['url_on_completion']);
        } catch (ApiErrorException $e) {
            Log::error("Failed to create the checkout: {$e->getMessage()}", [
                'order_id' => $order->id,
            ]);

            throw new OrderCheckoutNotCreatedException;
        }

        return StripeCheckout::create([
            'order_id' => $order->id,
            'stripe_checkout_session_id' => $session->id,
            'stripe_checkout_session_client_secret' => $session->client_secret,
            'stripe_customer_id' => $session->customer,
            'status' => $session->status,
            'payment_status' => $session->payment_status,
            'expires_at' => $session->expires_at,
        ]);
    }

    /**
     * Create a Stripe checkout session for the order.
     *
     * @param  Order  $order  The order to create the checkout session for.
     * @param  StripeClient  $stripe  The Stripe client to use to create the checkout session.
     * @param  string  $returnUrl  The URL to redirect to after the checkout session is completed.
     *
     * @throws ApiErrorException
     */
    private function createStripeCheckoutSession(Order $order, StripeClient $stripe, string $returnUrl): Session
    {
        // Find or create the Stripe customer by order recipient email.
        $stripeCustomer = $this->getOrCreateStripeCustomer($order, $stripe);

        // Create the Stripe checkout session for the Stripe customer.
        $payload = [
            'automatic_tax' => [
                'enabled' => true,
            ],
            'client_reference_id' => $order->uuid,
            'line_items' => [
                [
                    'price' => $order->item->stripe_price_id,
                    'quantity' => 1,
                ],
            ],
            'ui_mode' => 'embedded',
            //            'success_url' => route('purchase.success'),   // Only available for 'hosted' ui_mode
            //            'cancel_url' => route('purchase.new'),    // Only available for 'hosted' ui_mode
            'return_url' => $returnUrl,
            'redirect_on_completion' => 'if_required',  // Only redirect to the [return_url] after a redirect-based payment method is used (e.g. Apple Pay, Google Pay)
            'phone_number_collection' => [
                'enabled' => true,
            ],
            'billing_address_collection' => 'required',
            //            'shipping_address_collection' => [
            //                'allowed_countries' => ['US', 'CA'],
            //            ],  // This is required for Google Pay.
            'customer' => $stripeCustomer->id,
            'customer_update' => [
                'address' => 'auto',
                //                'shipping' => 'auto',   // This is required for Google Pay.
            ],
            'saved_payment_method_options' => [
                'payment_method_save' => 'enabled',
            ],
            'custom_text' => [
                'submit' => [
                    'message' => 'Your total may change based on sales tax calculated from your address.',
                ],
            ],
        ];

        if ($order->item->isRecurring()) {
            $payload['mode'] = 'subscription';
            $payload['subscription_data']['metadata'] = [
                'order_id' => $order->id,
                'order_uuid' => $order->uuid,
                'type' => $order->type->toString(),
            ];
        } else {
            $payload['mode'] = 'payment';
            $payload['invoice_creation'] = [
                'enabled' => true,
                'invoice_data' => [
                    'metadata' => [
                        'order_id' => $order->id,
                        'order_uuid' => $order->uuid,
                        'type' => $order->type->toString(),
                    ],
                ],
            ];
        }

        if ($order->isTypeTrial()) {
            $payload['subscription_data']['trial_period_days'] = 30;
        }

        return $stripe->checkout->sessions->create($payload);
    }

    /**
     * Get or create a Stripe customer by email.
     *
     * @throws ApiErrorException
     */
    private function getOrCreateStripeCustomer(Order $order, StripeClient $stripe): Customer
    {
        $customers = $stripe->customers->search([
            'query' => "email: '{$order->recipient_email}'",
            'limit' => 1,
        ]);

        if ($customers->data === null || count($customers->data) === 0) {
            Log::info('Stripe customer not found, creating new customer', [
                'email' => $order->recipient_email,
                'name' => $order->recipient_name,
            ]);

            return $stripe->customers->create([
                'email' => $order->recipient_email,
                'name' => $order->recipient_name,
            ]);
        }

        return $customers->data[0];
    }
}
