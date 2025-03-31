<?php

namespace App\Services;

use App\Enums\Brand;
use App\Enums\PlanType;
use App\Models\Campaign;
use App\Models\Plan;
use App\Models\Promo;
use App\Models\RenewalCoupon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

class PlanService
{
    public function searchPlanList(array $filters): Collection|LengthAwarePaginator
    {
        $filters = Arr::only($filters, [
            'brand_id',
            'type',
            'is_testing',
            'renewal_coupon_code',
            'promo_code',
            'nonce_code',
            'pagination',
            'page',
            'per_page',
        ]);

        $brand = Brand::from($filters['brand_id']);
        $isTesting = config('services.mol.testing_plans_enabled') && ($filters['is_testing'] ?? false);
        $promoCode = $filters['promo_code'] ?? '';
        $nonceCode = $filters['nonce_code'] ?? '';
        $renewalCouponCode = $filters['renewal_coupon_code'] ?? '';
        $pagination = $filters['pagination'] ?? false;
        $perPage = $filters['per_page'] ?? 10;
        $page = $filters['page'] ?? 1;

        $query = Plan::ofBrand($brand);

        if ($isTesting) {
            $query->testing();
        } else {
            // Apply promo and renewal coupon if provided, otherwise use the default campaign
            $promo = $this->getValidPromo($brand, $promoCode, $nonceCode);
            $renewalCoupon = $this->getValidRenewalCoupon($brand, $renewalCouponCode);
            $campaignId = $promo?->campaign_id ?? $renewalCoupon?->campaign_id ?? Campaign::default()->first()->id;
            $query->inCampaignIds([$campaignId]);
        }

        if (! empty($filters['type'])) {
            $query->ofType(PlanType::from($filters['type']));
        }

        if ($pagination) {
            return $query->paginate($perPage, ['*'], 'page', $page);
        }

        return $query->get();
    }

    /**
     * Get the valid promo by promo code and nonce code.
     */
    private function getValidPromo(Brand $brand, string $promoCode, string $nonceCode = ''): ?Promo
    {
        if (empty($promoCode)) {
            return null;
        }

        // Verify nonce code if promo code is ORIG
        if ($promoCode === 'ORIG') {
            if (empty($nonceCode) || ! verify_nonce_code($nonceCode)) {
                return null;
            }
        }

        return Promo::ofBrand($brand)->byCode($promoCode)->redeemable()->first();
    }

    /**
     * Get the valid renewal coupon by coupon code.
     */
    private function getValidRenewalCoupon(Brand $brand, string $couponCode): ?RenewalCoupon
    {
        return RenewalCoupon::ofBrand($brand)->byCode($couponCode)->redeemable()->first();
    }
}
