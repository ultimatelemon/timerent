<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\ApiController;
use App\Http\Resources\MemberResource;
use App\Http\Resources\ReservationResource;
use App\Models\Member;
use App\Models\Reservation;
use App\Models\Venue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class MemberController extends ApiController
{
    /**
     * Get the index of the resource
     *
     * @param Request $request
     * @param Venue $venue
     * @return JsonResponse
     */
    public function index(Request $request, Venue $venue): JsonResponse
    {
        $members = $venue->members();

        if($request->has('q'))
            $members = $members->where('name', 'ILIKE', "%{$request->q}%")
                ->orWhere('email', 'ILIKE', "%{$request->q}%");

        $members = $members->paginate(env('POSTS_PER_PAGE'));

        return $this->success(
            MemberResource::collection($members),
            collect($members)->only(['from', 'to', 'total', 'per_page', 'last_page', 'current_page'])->toArray(),
        );
    }

    /**
     * Show the specific resource
     *
     * @param Venue $venue
     * @param Member $member
     * @return JsonResponse
     */
    public function show(Venue $venue, Member $member): JsonResponse
    {
        return $this->success(new MemberResource($member));
    }

    /**
     * Update the specific resource
     *
     * @param Member $member
     * @param Request $request
     * @param Venue $venue
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Venue $venue, Member $member, Request $request): JsonResponse
    {
        $validatedRequest = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:48',
            'email' => 'required|string|email|email:rfc,dns|unique:members,email,'.$member->id,
            'pay_on_invoice' => 'required|boolean',
            'loyality_points' => 'required|numeric|integer',
        ]);

        $member = $venue->members()->findOrFail($member->id);
        $member->update($validatedRequest->validated());

        return $this->success(new MemberResource($member));
    }

    public function current(Request $request): JsonResponse
    {
        if($request->member)
            return $this->success(new MemberResource($request->member));

        return $this->success();
    }
}
