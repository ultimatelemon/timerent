<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRole;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use App\Models\Venue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RoleController extends ApiController implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('hasPermissions:VIEW_ROLES', only: ['index', 'show']),
            new Middleware('hasPermissions:MANAGE_ROLES', only: ['store', 'update', 'destroy']),
        ];
    }

    /**
     * Display a listing of the resource
     *
     * @param Request $request
     * @param Venue $venue
     * @return JsonResponse
     */
    public function index(Request $request, Venue $venue): JsonResponse
    {
        $roles = $venue->roles();

        if($request->has('q'))
            $roles = $roles->where('name', 'ILIKE', "%{$request->q}%");

        $roles = $roles->paginate(env('POSTS_PER_PAGE'));

        return $this->success(
            RoleResource::collection($roles),
            collect($roles)->only(['from', 'to', 'total', 'per_page', 'last_page', 'current_page'])->toArray(),
        );
    }


    /**
     * Store a new role
     *
     * @param StoreRole $request
     * @param Venue $venue
     * @return JsonResponse
     */
    public function store(StoreRole $request, Venue $venue): JsonResponse
    {
        $validatedRequest = $request->validated();
        $role = new Role;
        $role->venue_id = $venue->id;
        $role->name = $validatedRequest['name'];
        $role->bitfield = $validatedRequest['bitfield'];
        $role->save();

        return $this->success(new RoleResource($role));
    }


    /**
     * Display the specific role
     *
     * @param Venue $venue
     * @param Role $role
     * @return JsonResponse
     */
    public function show(Venue $venue, Role $role): JsonResponse
    {
        $role = $venue->roles()->where('id', $role->id)->firstOrFail();
        return $this->success(new RoleResource($role));
    }


    /**
     * Update the role
     *
     * @param Venue $venue
     * @param Role $role
     * @param StoreRole $request
     *
     * @return JsonResponse
     */
    public function update(Venue $venue, Role $role, StoreRole $request): JsonResponse
    {
        ray($request->all());
        $validatedRequest = $request->validated();
        $role->update($validatedRequest);
        return $this->success(new RoleResource($role));
    }
}
