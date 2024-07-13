<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\ApiController;
use App\Models\PaymentProvider;
use Illuminate\Http\JsonResponse;

class PaymentProviderController extends ApiController
{
    /**
     * Display an index of the available payment providers
     *
     * @return JsonResponse
     */
    public function available(): JsonResponse
    {
        $providers = PaymentProvider::where('is_active', true)->get();
        return $this->success($providers);
    }
}
