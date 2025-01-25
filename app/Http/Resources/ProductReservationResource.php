<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductReservationResource extends JsonResource
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
            'description' => $this->description,
            'price' => $this->price,
            'is_active' => $this->is_active,
            'tax_percentage' => $this->tax_percentage,
            'max_per_day' => $this->max_per_day,
            'max_per_reservation' => $this->max_per_reservation,
            'price_per_timeblock' => $this->price_per_timeblock,
            'count' => $this->pivot->count ?? null,
        ];
    }
}
