<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VenueResource extends JsonResource
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
            'subdomain' => $this->subdomain,
            'address' => $this->address,
            'postal_code' => $this->postal_code,
            'city' => $this->city,
            'email' => $this->email,
            'phone' => $this->phone,
            'coc_number' => $this->coc_number,
            'tax_number' => $this->tax_number,
            'payment_service_provider' => $this->payment_service_provider,
            'stripe_current_period_ends_at' => $this->stripe_current_period_ends_at,
            'plan' => new PlanResource($this->plan),
            'unit_count' => $this->units()->count(),
        ];
    }
}
