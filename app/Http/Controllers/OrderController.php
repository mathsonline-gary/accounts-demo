<?php

namespace App\Http\Controllers;

use App\Enums\OrderType;
use App\Exceptions\InvalidOrderItemException;
use App\Exceptions\RecentPaidOrderExistsException;
use App\Http\Requests\Orders\StoreOrderRequest;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderService $orderService,
    ) {}

    public function store(StoreOrderRequest $request): JsonResponse
    {
        Gate::authorize('create', [Order::class, OrderType::from($request->integer('type_id'))]);

        try {
            $order = $this->orderService->createOrder([
                'brand_id' => $request->integer('brand_id'),
                'type_id' => $request->integer('type_id'),
                'item_id' => $request->integer('item_id'),
                'creator_id' => $request->user?->id,
                'recipient_email' => $request->input('recipient_email'),
                'recipient_first_name' => $request->input('recipient_first_name'),
                'recipient_last_name' => $request->input('recipient_last_name'),
                'reference_code' => $request->input('reference_code'),
                'reference_code_type_id' => $request->integer('reference_code_type_id'),
            ]);
        } catch (InvalidOrderItemException) {
            return response()->json([
                'message' => 'The order item is invalid.',
                'errors' => [
                    'item_id' => 'The order item is invalid.',
                ],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (RecentPaidOrderExistsException) {
            return response()->json([
                'message' => 'The user has made a purchase recently.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (AuthorizationException) {
            return response()->json([
                'message' => 'The user is not authorized to create this order.',
            ], Response::HTTP_FORBIDDEN);
        }

        return response()->json([
            'message' => 'Order created successfully.',
            'data' => [
                'order' => $order->uuid,
            ],
        ]);
    }
}
