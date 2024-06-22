<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\ApiController;
use App\Http\Resources\PlanResource;
use App\Models\Plan;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;

class PlanController extends ApiController
{

    /**
     * Index of the resource
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {

    }

    /**
     * Get the available resources
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function available(Request $request): JsonResponse
    {
        return $this->success(PlanResource::collection(Plan::where('is_active', true)->get()));
    }
}
