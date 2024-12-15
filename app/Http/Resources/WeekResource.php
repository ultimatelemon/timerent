<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WeekResource extends JsonResource
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
            'template' => $this->template,
            'template_name' => $this->template_name,
//            'changed_from_origin' => $this->changed_from_origin,
            'changed_from_origin' => $this->changed_from_origin,
            'unit' => new UnitResource($this->unit),
            'year' => $this->year,
            'week' => $this->week,
            'interval' => $this->interval,
            'price' => $this->price,
        ];
    }
}
