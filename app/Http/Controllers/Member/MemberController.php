<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\ApiController;
use App\Http\Resources\MemberResource;
use App\Http\Resources\ReservationResource;
use App\Models\Member;
use App\Models\Reservation;
use App\Models\Venue;
use Carbon\Carbon;
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
            'group_id' => 'nullable|sometimes|uuid|exists:groups,id',
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

    /**
     * Display a listing of the resource
     *
     * @param Request $request
     * @param Venue $venue
     * @return JsonResponse
     */
    public function reservations(Request $request, Venue $venue, Member $member): JsonResponse
    {
        $reservations = $member->reservations();

        if($request->has('q'))
            $reservations = $reservations->where('id', 'ILIKE', "%{$request->q}%")
                ->orWhere('email', 'ILIKE', "%{$request->q}%");

        if($request->has('max'))
            $reservations->max($request->max);

        if($request->has('date') && $request->date === 'today')
            $reservations->where('date', Carbon::today());

        $reservations = $reservations->orderBy('date', 'asc');

        $reservations = $reservations->paginate(env('POSTS_PER_PAGE'));

        return $this->success(
            ReservationResource::collection($reservations),
            collect($reservations)->only(['from', 'to', 'total', 'per_page', 'last_page', 'current_page'])->toArray(),
        );
    }
}
