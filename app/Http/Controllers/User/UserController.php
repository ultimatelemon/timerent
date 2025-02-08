<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Http\Resources\RoleResource;
use App\Http\Resources\TokenResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserVenueResource;
use App\Models\User;
use App\Models\UserVenue;
use App\Models\Venue;
use App\Rules\CaseInsensitiveExists;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends ApiController
{

    public static function middleware(): array
    {
        return [
            new Middleware('hasPermissions:VIEW_USERS', only: ['index', 'show']),
            new Middleware('hasPermissions:MANAGE_USERS', only: ['store', 'update', 'destroy']),
        ];
    }

    /**
     * Return the current user object
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function current(Request $request): JsonResponse
    {
        if($request->venue == 'undefined' || !$request->venue) return $this->success(new UserResource($request->user()));

        $venue = Venue::findOrFail($request->venue);

        $userVenue = UserVenue::where('user_id', $request->user()->id)
            ->where('venue_id', $venue->id)->firstOrFail();

        return $this->success(
            [
                'user' => new UserVenueResource($userVenue),
                'role' => new RoleResource($userVenue->role),
            ]
        );
    }

    /**
     * Return the current user venue object
     *
     * @param Request $request
     * @param Venue $venue
     * @return JsonResponse
     */
    public function currentUserVenue(Request $request, $venue): JsonResponse
    {
        $userVenue = UserVenue::where('user_id', $request->user()->id)
            ->where('venue_id', $venue->id);


        return $this->success(
            [
                'user' => new UserResource($userVenue->user),
                'role' => new RoleResource($userVenue->role),
            ]
        );
//        return $this->success(new UserResource($request->user()));
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
        $users = UserVenue::where('venue_id', $venue->id);

        if($request->has('q'))
            $users = $users->where('name', 'ILIKE', "%{$request->q}%")
                ->orWhere('email', 'ILIKE', "%{$request->q}%");

        $users = $users->paginate(env('POSTS_PER_PAGE'));

        return $this->success(
            UserVenueResource::collection($users),
            collect($users)->only(['from', 'to', 'total', 'per_page', 'last_page', 'current_page'])->toArray(),
        );
    }

    public function store(Request $request, Venue $venue): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email:rfc,dns', new CaseInsensitiveExists('users', 'email')],
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::where('email', strtolower($validated['email']))->firstOrFail();

        if(UserVenue::where(['user_id' => $user->id, 'venue_id' => $venue->id])->exists()) return $this->error(['exists' => 'Gebruiker is al gekoppeld']);

        UserVenue::create(['user_id' => $user->id, 'venue_id' => $venue->id, 'role_id' => $validated['role_id']]);

        return $this->success();
    }

    public function update(Request $request, Venue $venue, User $user): JsonResponse
    {
        $validated = Validator::make($request->all(), [
            'role_id' => 'required|exists:roles,id',
        ])->validated();

        UserVenue::where(['user_id' => $user->id, 'venue_id' => $venue->id])->update(['role_id' => $validated['role_id']]);

        return $this->success();
    }

    public function destroy(Venue $venue, User $user): JsonResponse
    {
        $user = UserVenue::where(['user_id' => $user->id, 'venue_id' => $venue->id])->firstOrFail();
        $user->delete();
        return $this->success();
    }

    public function updateCurrentUser(Request $request): JsonResponse
    {
        $user = Auth::user();
        if(!$user) return $this->error();
        $validatedRequest = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:48',
            'email' => 'required|string|email|email:rfc,dns|unique:users,email,'.$user->id,
        ]);
        $user->update($validatedRequest->validate());
        return $this->success(new UserResource($user));
    }
}
