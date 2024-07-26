<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
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
            'number' => strtoupper(explode('-', $this->id)[0]),
            'email' => $this->email,
            'payment_amount' => $this->payment_amount,
            'tax_low' => $this->tax_low,
            'tax_high' => $this->tax_high,
            'paid_at' => $this->paid_at,
            'created_at' => $this->created_at,
        ];
    }
}
