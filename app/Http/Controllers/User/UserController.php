<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Http\Resources\TokenResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\UserVenue;
use App\Models\Venue;
use App\Rules\CaseInsensitiveExists;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserController extends ApiController
{

//    public static function middleware(): array
//    {
//        return [
//            new Middleware('hasPermissions:VIEW_USERS', only: ['index', 'show']),
//            new Middleware('hasPermissions:MANAGE_USERS', only: ['store', 'update', 'destroy']),
//        ];
//    }

    /**
     * Return the current user object
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function current(Request $request): JsonResponse
    {
        return $this->success(UserVenue::where('user_id', $request->user()->id)->first());
        return $this->success(new UserResource($request->user()));
    }

    /**
     * Get the user and the token
     *
     * @param Request $request
     * @return array
     */

    public function token(Request $request): array
    {
        return ['user' => new UserResource($request->user()), 'token' => new TokenResource($request->user()->currentAccessToken())];
    }

    /**
     * Get the index of the resource
     *
     * @param Request $request
     * @param Venue $venue
     * @return JsonResponse
     */
    public function index(Request $request, Venue $venue): JsonResponse
    {
        $users = $venue->users();

        if($request->has('q'))
            $users = $users->where('name', 'ILIKE', "%{$request->q}%")
                ->orWhere('email', 'ILIKE', "%{$request->q}%");

        $users = $users->paginate(env('POSTS_PER_PAGE'));

        return $this->success(
            UserResource::collection($users),
            collect($users)->only(['from', 'to', 'total', 'per_page', 'last_page', 'current_page'])->toArray(),
        );
    }

    public function store(Request $request, Venue $venue): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email:rfc,dns', new CaseInsensitiveExists('users', 'email')],
        ]);

        $user = User::where('email', strtolower($validated['email']))->firstOrFail();

        if(UserVenue::where(['user_id' => $user->id, 'venue_id' => $venue->id])->exists()) return $this->error(['exists' => 'Gebruiker is al gekoppeld']);

        UserVenue::create(['user_id' => $user->id, 'venue_id' => $venue->id, 'accepted' => true]);

        return $this->success();
    }

    public function destroy(Venue $venue, User $user): JsonResponse
    {
        $user = UserVenue::where(['user_id' => $user->id, 'venue_id' => $venue->id])->firstOrFail();
        $user->delete();
        return $this->success();
    }
}
