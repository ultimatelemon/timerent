<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TokenResource extends JsonResource
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
            'name' => $this->name,
//            'connected' => $this->type === 'device' ? Device
            'type' => $this->type,

            'expires_at' => $this->expires_at ? Carbon::parse($this->expires_at) : null,
            'last_used_at' => $this->last_used_at,
        ];
    }
}
