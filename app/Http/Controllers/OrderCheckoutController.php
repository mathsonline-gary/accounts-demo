<?php

namespace App\Http\Controllers;

use App\Exceptions\OrderCheckouts\OrderCheckoutAlreadyExistsException;
use App\Exceptions\OrderCheckouts\OrderCheckoutNotCreatedException;
use App\Exceptions\Orders\OrderAlreadyCancelledException;
use App\Exceptions\Orders\OrderAlreadyExpiredException;
use App\Exceptions\Orders\OrderAlreadyPaidException;
use App\Exceptions\Orders\OrderNotFoundException;
use App\Http\Requests\OrderCheckouts\StoreOrderCheckoutRequest;
use App\Http\Resources\OrderCheckoutResource;
use App\Services\OrderCheckoutService;
use Illuminate\Http\JsonResponse;

class OrderCheckoutController extends Controller
{
    public function __construct(
        private readonly OrderCheckoutService $orderCheckoutService,
    ) {}

    public function store(StoreOrderCheckoutRequest $request, string $uuid): JsonResponse
    {
        try {
            $checkout = $this->orderCheckoutService->createCheckout([
                'order_uuid' => $uuid,
                'url_on_completion' => $request->input('url_on_completion'),
            ]);
        } catch (OrderCheckoutNotCreatedException) {
            return response()->json([
                'message' => 'Failed to create checkout.',
            ], 500);
        } catch (OrderNotFoundException) {
            return response()->json([
                'message' => 'Order not found.',
            ], 404);
        } catch (OrderCheckoutAlreadyExistsException|OrderAlreadyCancelledException|OrderAlreadyExpiredException|OrderAlreadyPaidException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 409);
        }

        return response()->json([
            'message' => 'Order checkout created successfully.',
            'data' => new OrderCheckoutResource($checkout),
        ], 201);
    }
}
