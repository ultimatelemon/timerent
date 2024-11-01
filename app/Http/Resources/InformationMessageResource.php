<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InformationMessageResource extends JsonResource
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
            'code' => $this->code,
            'message' => $this->message,
            'employee' => [
                'id' => $this->employee?->id,
                'name' => $this->employee?->name,
                'role' => [
                    'name' => $this->employee?->role?->name,
                ]
            ],
            'created_at' => $this->created_at,
            'solved_at' => $this->solved_at
        ];
    }
}
