<?php

namespace App\Http\Controllers;

use App\Enums\OrderType;
use App\Http\Requests\Orders\StoreOrderRequest;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderService $orderService,
    ) {}

    public function store(StoreOrderRequest $request): JsonResponse
    {
        $order = $this->orderService->createOrder([
            'type' => OrderType::from($request->input('type')),
            'plan_id' => (int) $request->input('plan_id'),
            'renewal_coupon_code' => $request->input('renewal_coupon_code'),
            'promo_code' => $request->input('promo_code'),
            'nonce_code' => $request->input('nonce_code'),
            'creator' => $request->user(),
        ]);

        return response()->json([
            'message' => 'Order created successfully',
            'data' => [
                'order' => $order->uuid,
            ],
        ]);
    }

}
