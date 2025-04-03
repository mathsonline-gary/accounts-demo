<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $hash
 * @property string $country_name
 * @property string $country_code
 * @property string $domain
 * @property string $marketing_website_url
 * @property string $noreply_email
 * @property string $noreply_name
 * @property string $support_email
 * @property string $support_name
 * @property string $feedback_email
 * @property string $feedback_name
 * @property string $support_phone
 * @property string|null $social_media_facebook
 * @property string|null $social_media_instagram
 * @property string|null $social_media_linkedin
 * @property string|null $stripe_publishable_key
 * @property string|null $stripe_secret_key
 * @property string|null $stripe_webhook_secret
 * @property string|null $keap_account_key
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereCountryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereFeedbackEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereFeedbackName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereKeapAccountKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereMarketingWebsiteUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereNoreplyEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereNoreplyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereSocialMediaFacebook($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereSocialMediaInstagram($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereSocialMediaLinkedin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereStripePublishableKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereStripeSecretKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereStripeWebhookSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereSupportEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereSupportName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereSupportPhone($value)
 */
	class Brand extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $brand_id
 * @property string $code
 * @property string|null $description
 * @property array<array-key, mixed> $tags
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign default()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereTags($value)
 */
	class Campaign extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $uuid
 * @property int $brand_id
 * @property int $type_id 1 = new purchase, 2 = renewal, 3 = trial, 4 = coupon redemption, 5 = gift, 6 = offline
 * @property int|null $creator_id The ID of the user who created the order.
 * @property int|null $recipient_id The user ID of the recipient.
 * @property string $recipient_email The snapshot of the recipient email.
 * @property string|null $recipient_first_name The snapshot of the recipient first name.
 * @property string|null $recipient_last_name The snapshot of the recipient last name.
 * @property string|null $billing_address_line_1
 * @property string|null $billing_address_line_2
 * @property string|null $billing_city
 * @property string|null $billing_state
 * @property string|null $billing_postal_code
 * @property string|null $billing_country
 * @property int $item_id The item ID of the order, i.e. plan ID.
 * @property float $amount_subtotal The amount of the order before tax is applied.
 * @property float|null $amount_tax The amount of tax applied to the order.
 * @property string|null $reference_code
 * @property int|null $reference_code_type_id 1: Promo code, 2: Renewal coupon code, 3: Coupon code, 4: Offline sales code
 * @property string|null $reference_code_validation_error The reason the reference code is invalid. Null if the reference code is valid or not provided.
 * @property \App\Enums\OrderStatus $status
 * @property \Illuminate\Support\Carbon|null $paid_at
 * @property \App\Enums\PaymentGateway|null $paid_via The payment gateway used to pay for the order
 * @property bool $is_hidden
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $amount_total
 * @property \App\Enums\Brand $brand
 * @property-read \App\Models\User|null $creator
 * @property-read \App\Models\Plan|null $item
 * @property-read \App\Models\User|null $recipient
 * @property-read mixed $reference_code_type
 * @property-read mixed $type
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order byRecipientEmail(string $recipientEmail)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order byStatus(\App\Enums\OrderStatus $status)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order ofType(\App\Enums\OrderType $type)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereAmountSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereAmountTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereBillingAddressLine1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereBillingAddressLine2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereBillingCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereBillingCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereBillingPostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereBillingState($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereIsHidden($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePaidVia($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereRecipientEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereRecipientFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereRecipientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereRecipientLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereReferenceCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereReferenceCodeTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereReferenceCodeValidationError($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUuid($value)
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $brand_id
 * @property string|null $stripe_price_id
 * @property string|null $code
 * @property string|null $description
 * @property float|null $price_original The original price
 * @property float|null $price The final price
 * @property float|null $price_saved
 * @property bool $is_recurring
 * @property int|null $period_in_months The total membership duration in months (including the extra months)
 * @property int $extra_months The additional months compared to the regular plan (e.t. 12 months)
 * @property int $student_limit
 * @property \App\Enums\PlanType $type 1: for standard customers; 2: for homeschoolers; 3: for custom orders; 4: deprecated plans
 * @property string $currency
 * @property-read mixed $billing_period
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Campaign> $campaigns
 * @property-read int|null $campaigns_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan forFamily()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan forSingleStudent()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan inCampaignIds(array $campaignIds)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan isHidden(bool $isHidden)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan isRecurring(bool $isRecurring)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan ofBrand(\App\Enums\Brand $brand)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan ofPeriodInMonths(int $periodInMonths)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan ofStudentLimit(int $studentLimit)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan ofType(\App\Enums\PlanType $type)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan testing()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan whereExtraMonths($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan whereIsRecurring($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan wherePeriodInMonths($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan wherePriceOriginal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan wherePriceSaved($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan whereStripePriceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan whereStudentLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plan whereType($value)
 */
	class Plan extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $brand_id
 * @property int $campaign_id
 * @property string $code
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $expires_at The date and time the promo will expire, null for no expiration
 * @property int $redemption_count The number of times the promo has been redeemed
 * @property bool $is_active Whether the promo is currently active for redemptions
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Campaign|null $campaign
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promo byCode(string $code)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promo ofBrand(\App\Enums\Brand $brand)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promo query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promo redeemable()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promo whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promo whereCampaignId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promo whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promo whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promo whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promo whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promo whereRedemptionCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promo whereUpdatedAt($value)
 */
	class Promo extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $brand_id
 * @property int $campaign_id
 * @property string $code
 * @property string|null $note
 * @property int $authorized_redeemer_id The ID of the user that is authorized to redeem the coupon
 * @property \Illuminate\Support\Carbon|null $expires_at The date and time the renewal coupon will expire. Default is 7 days from the date of creation.
 * @property \Illuminate\Support\Carbon|null $redeemed_at The date and time the renewal coupon was redeemed. Null if not redeemed.
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Campaign|null $campaign
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RenewalCoupon byCode(string $code)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RenewalCoupon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RenewalCoupon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RenewalCoupon ofBrand(\App\Enums\Brand $brand)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RenewalCoupon query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RenewalCoupon redeemable()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RenewalCoupon whereAuthorizedRedeemerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RenewalCoupon whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RenewalCoupon whereCampaignId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RenewalCoupon whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RenewalCoupon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RenewalCoupon whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RenewalCoupon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RenewalCoupon whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RenewalCoupon whereRedeemedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RenewalCoupon whereUpdatedAt($value)
 */
	class RenewalCoupon extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $order_id
 * @property string $stripe_checkout_session_id The ID of the Stripe checkout session
 * @property string $stripe_checkout_session_client_secret The client secret of the Stripe checkout session
 * @property string|null $stripe_customer_id The ID of the Stripe customer created from the checkout session. Null if the checkout session is not for a customer.
 * @property string|null $stripe_subscription_id The ID of the Stripe subscription created from the checkout session. Null if the checkout session is not for a subscription.
 * @property string|null $stripe_invoice_id The ID of the Stripe invoice created from the checkout session.
 * @property string $status The status of the Stripe checkout session, one of "open", "complete", or "expired".
 * @property string $payment_status The payment status of the Stripe checkout session, one of "unpaid", "paid", or "no_payment_required".
 * @property \Illuminate\Support\Carbon $expires_at The date and time the Stripe checkout session expires.
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Order|null $order
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StripeCheckout newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StripeCheckout newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StripeCheckout query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StripeCheckout whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StripeCheckout whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StripeCheckout whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StripeCheckout whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StripeCheckout wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StripeCheckout whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StripeCheckout whereStripeCheckoutSessionClientSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StripeCheckout whereStripeCheckoutSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StripeCheckout whereStripeCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StripeCheckout whereStripeInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StripeCheckout whereStripeSubscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StripeCheckout whereUpdatedAt($value)
 */
	class StripeCheckout extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $order_id
 * @property string $stripe_payment_intent_id The ID of the Stripe payment intent.
 * @property-read \App\Models\Order|null $order
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StripePayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StripePayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StripePayment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StripePayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StripePayment whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StripePayment whereStripePaymentIntentId($value)
 */
	class StripePayment extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $brand_id
 * @property int $role_id 1: Admin, 2: Student, 3: Teacher, 4: Customer
 * @property string $first_name
 * @property string $last_name
 * @property string|null $email
 * @property string $username
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $ip_address
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Brand|null $brand
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserExternalAccount> $externalAccounts
 * @property-read int|null $external_accounts_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property \App\Enums\UserRole $role
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User byEmail(string $email)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User bySocialProviderId(string $provider, string $id)
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUsername($value)
 */
	class User extends \Eloquent implements \Tymon\JWTAuth\Contracts\JWTSubject {}
}

namespace App\Models{
/**
 * 
 *
 * @property \App\Enums\ExternalService $provider
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserExternalAccount byProvider(\App\Enums\ExternalService $provider)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserExternalAccount byProviderUserId(\App\Enums\ExternalService $provider, string $providerUserId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserExternalAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserExternalAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserExternalAccount ofUser(int $userId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserExternalAccount query()
 */
	class UserExternalAccount extends \Eloquent {}
}

