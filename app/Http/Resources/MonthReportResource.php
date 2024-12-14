<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MonthReportResource extends JsonResource
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
            'venue' => [
                'id' => $this->venue->id,
                'name' => $this->venue->name,
            ],
            'year' => $this->year,
            'month' => $this->month,
            'period_from' => $this->period_from,
            'period_to' => $this->period_to,
            'total_revenue' => $this->total_revenue,
            'tax_amount_high' => $this->tax_amount_high,
            'tax_amount_low' => $this->tax_amount_low,
            'customer_count' => $this->customer_count,
            'reservation_count' => $this->reservation_count,
            'available_at' => $this->available_at,
        ];
    }
}
