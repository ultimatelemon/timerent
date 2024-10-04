<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Support\TicketResource;
use App\Models\Support\Ticket;
use App\Models\Support\TicketMessage;
use App\Models\Venue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TicketController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Venue $venue): JsonResponse
    {
        $tickets = $venue->tickets();

        if($request->has('q'))
            $tickets = $tickets->where('title', 'ILIKE', "%{$request->q}%");

        $tickets = $tickets->paginate(env('POSTS_PER_PAGE'));

        return $this->success(
            TicketResource::collection($tickets),
            collect($tickets)->only(['from', 'to', 'total', 'per_page', 'last_page', 'current_page'])->toArray(),
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Venue $venue): JsonResponse
    {
        $validatedRequest = Validator::make($request->all(), [
            'title' => 'required|string',
            'type' => 'required|string',
            'message' => 'required|string'
        ])->validated();

        $ticket = new Ticket;
        $ticket->venue_id = $venue->id;
        $ticket->title = $validatedRequest['title'];
        $ticket->type = $validatedRequest['type'];
        $ticket->message = $validatedRequest['message'];
        $ticket->user_id = Auth::user()->id;
        $ticket->save();
        $venue->tickets()->save($ticket);

        return $this->success();
    }

    /**
     * Display the specified resource.
     */
    public function show(Venue $venue, Ticket $ticket): JsonResponse
    {
        return $this->success(new TicketResource($ticket));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
