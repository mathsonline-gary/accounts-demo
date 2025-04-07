<?php

namespace App\Http\Controllers;

use App\Enums\OrderType;
use App\Exceptions\Orders\OrderNotFoundException;
use App\Exceptions\Orders\RecentPaidOrderAlreadyExistsException;
use App\Exceptions\Plans\PlanNotFoundException;
use App\Http\Requests\Orders\ShowOrderRequest;
use App\Http\Requests\Orders\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
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
                'creator_id' => $request->user()?->id,
                'recipient_email' => $request->input('recipient_email'),
                'recipient_first_name' => $request->input('recipient_first_name'),
                'recipient_last_name' => $request->input('recipient_last_name'),
                'reference_code' => $request->input('reference_code'),
                'reference_code_type_id' => $request->integer('reference_code_type_id'),
                'source' => $request->header('Referer'),
                'with_relations' => $request->array('include'),
            ]);
        } catch (RecentPaidOrderAlreadyExistsException) {
            return response()->json([
                'message' => 'A recent paid order already exists for this user.',
            ], 422);
        } catch (PlanNotFoundException) {
            return response()->json([
                'message' => 'The order item is invalid.',
            ], 422);
        }

        return response()->json([
            'message' => 'Order created successfully.',
            'data' => new OrderResource($order),
        ]);
    }

    public function show(ShowOrderRequest $request, string $uuid): JsonResponse
    {
        try {
            $order = $this->orderService->getOrderByUuid($uuid, [
                'with_relations' => $request->array('include'),
            ]);
        } catch (OrderNotFoundException) {
            return response()->json([
                'message' => 'Order not found.',
            ], 404);
        }

        return response()->json([
            'message' => 'Order retrieved successfully.',
            'data' => new OrderResource($order),
        ]);
    }
}
