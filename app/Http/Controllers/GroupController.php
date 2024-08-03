<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGroup;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use App\Models\Venue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class GroupController extends ApiController
{
//    public static function middleware(): array
//    {
//        return [
//            new Middleware('hasPermissions:VIEW_GROUPS', only: ['index', 'show']),
//            new Middleware('hasPermissions:MANAGE_GROUPS', only: ['store', 'update', 'destroy']),
//        ];
//    }

    /**
     * Display a listing of the resource
     *
     * @param Request $request
     * @param Venue $venue
     * @return JsonResponse
     */
    public function index(Request $request, Venue $venue): JsonResponse
    {
        $groups = $venue->groups();

        if($request->has('q'))
            $groups = $groups->where('name', 'ILIKE', "%{$request->q}%");

        $groups = $groups->paginate(env('POSTS_PER_PAGE'));

        return $this->success(
            GroupResource::collection($groups),
            collect($groups)->only(['from', 'to', 'total', 'per_page', 'last_page', 'current_page'])->toArray(),
        );
    }

    public function store(StoreGroup $request, Venue $venue): JsonResponse
    {
        $group = Group::create($request->validated());
        return $this->success(new GroupResource($group));
    }
}
