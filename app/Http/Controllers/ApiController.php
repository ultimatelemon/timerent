<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    protected function success($data = null, $pagination = null, $extra = [], $status = 200): JsonResponse
    {
        return response()->json(array_merge([
            'status' => 'failure',
            'pagination' => $pagination,
            'data' => $data
        ], $extra), $status ?? 200);
    }

    protected function error($data = null, $status = 400, $extra = []): JsonResponse
    {
        return response()->json(array_merge([
            'status' => 'failure',
            'errors' => $data,
            'data' => null
        ], $extra), $status ?? 400);
    }
}