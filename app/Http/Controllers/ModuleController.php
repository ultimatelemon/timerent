<?php

namespace App\Http\Controllers;

use App\Http\Resources\ModuleResource;
use App\Models\Modules\Module;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ModuleController extends ApiController
{
    public function index(): JsonResponse
    {
        $modules = Module::all();
        return $this->success(ModuleResource::collection($modules));
    }
}
