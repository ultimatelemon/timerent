<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserVenueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'owner' => $this->owner,
            'venue' => new VenueResource($this->whenLoaded('venue')),
            'user' => new UserResource($this->user),
            'created_at' => $this->created_at,
        ];
    }
}
