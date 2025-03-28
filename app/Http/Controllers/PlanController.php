<?php

namespace App\Http\Controllers;

use App\Http\Requests\Plans\SearchPlanRequest;
use App\Http\Resources\PlanResource;
use App\Services\PlanService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PlanController extends Controller
{
    public function __construct(
        private readonly PlanService $planService,
    ) {}

    public function search(SearchPlanRequest $request): AnonymousResourceCollection
    {
        $plans = $this->planService->searchPlanList([
            ...$request->validated(),
        ]);

        return PlanResource::collection($plans);
    }
}
