<?php

namespace App\Http\Controllers;

use App\Enums\OrderType;
use App\Enums\ReferralCodeType;
use App\Exceptions\InvalidOrderItemException;
use App\Exceptions\RecentPaidOrderExistsException;
use App\Http\Requests\Orders\StoreOrderRequest;
use App\Services\OrderService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderService $orderService,
    ) {}

    public function store(StoreOrderRequest $request): JsonResponse
    {
        try {
            $order = $this->orderService->createOrder([
                'type' => OrderType::from($request->integer('type')),
                'item_id' => $request->integer('item_id'),
                'creator' => $request->user(),
                'referral_code' => $request->input('referral_code'),
                'referral_code_type' => ReferralCodeType::from($request->integer('referral_code_type')),
            ]);
        } catch (InvalidOrderItemException) {
            return response()->json([
                'message' => 'The order item is invalid.',
                'errors' => [
                    'item_id' => 'The order item is invalid.',
                ],
            ], 422);
        } catch (RecentPaidOrderExistsException) {
            return response()->json([
                'message' => 'The user has made a purchase recently.',
            ], 422);
        } catch (AuthorizationException) {
            return response()->json([
                'message' => 'The user is not authorized to create this order.',
            ], 403);
        }

        return response()->json([
            'message' => 'Order created successfully.',
            'data' => [
                'order' => $order->uuid,
            ],
        ]);
    }
}
