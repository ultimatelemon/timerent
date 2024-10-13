<?php

namespace App\Http\Resources\Support;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketMessageResource extends JsonResource
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
            'is_employee' => $this->is_employee,
            'user' => ($this->is_employee ? $this->employee : $this->user),
            'message' => $this->message,
            'role_name' => $this->role_name,
            'created_at' => $this->created_at,
        ];
    }
}
