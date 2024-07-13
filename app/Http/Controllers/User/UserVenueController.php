<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Http\Resources\UserVenueResource;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserVenueController extends ApiController
{
    public function index(Request $request, User $user): JsonResponse
    {
        $user_venues = $user->user_venues()->with('venue');
        if($request->has('q'))
            $user_venues = $user_venues->where('user_venues.name', 'ILIKE', "%{$request->q}%");

        $user_venues = $user_venues->paginate(env('POST_PER_PAGE'));

        return $this->success(UserVenueResource::collection($user_venues), null, $user_venues->toArray());
    }
}
