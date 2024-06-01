<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVenue;
use App\Http\Resources\VenueResource;
use App\Models\UserVenue;
use App\Models\Venue;
use Illuminate\Http\Request;

class VenueController extends ApiController
{
    public function store(StoreVenue $request)
    {
        $venue = Venue::create($request->all());

        UserVenue::create(['user_id' => $request->user()->id, 'venue_id' => $venue->id, 'accepted' => true]);

        return $this->success(new VenueResource($venue), null, ['message' => 'Store successfully']);
    }
}
