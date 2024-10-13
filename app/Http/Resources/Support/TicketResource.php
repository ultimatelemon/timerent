<?php

namespace App\Http\Resources\Support;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
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
            'human_id' => strtoupper(explode('-', $this->id)[0]),
            'title' => $this->title,
            'type' => $this->type,
            'status' => $this->status,
            'user' => [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
            ],
            'messages' => TicketMessageResource::collection($this->ticket_messages),
            'created_at' => $this->created_at,
            'closed_at' => $this->closed_at,
        ];
    }
}
