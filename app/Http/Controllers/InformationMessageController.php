<?php

namespace App\Http\Controllers;

use App\Http\Resources\InformationMessageResource;
use App\Models\InformationMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InformationMessageController extends ApiController
{
    public function index(): JsonResponse
    {
        $tri = InformationMessage::query()->where('solved_at', null);

        $tri = $tri->paginate(env('POSTS_PER_PAGE'));

        return $this->success(
            InformationMessageResource::collection($tri),
            collect($tri)->only(['from', 'to', 'total', 'per_page', 'last_page', 'current_page'])->toArray(),
        );
    }
}
