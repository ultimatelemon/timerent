<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
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
            'number' => $this->number ?? strtoupper(explode('-', $this->id)[0]),
            'date' => $this->date,
            'payment_amount' => $this->payment_amount,
            'payment_status' => $this->payment_status,
            'payment_url' => $this->payment_url,
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'user' => [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
            ],
            'timeblocks' => ReservationTimeblockResource::collection($this->timeblocks),
            'unit' => [
                'id' => $this->unit_id,
                'name' => $this->unit_name,
            ],
            'cancel_allowed' => true,
//            'cancel_allowed' => Carbon::parse($this->date)->setHour(explode(':', explode(' ', $this->timeblocks->first()->from)[1])[0])->setMinute(0)->setSecond(0) >= Carbon::now()->addHours(Setting::where('key', 'cancel_hours_before_reservation')->first()->value),
            'products' => ProductResource::collection($this->products),
            'created_at' => $this->created_at,
        ];

        // TODO: Settings for max hours before cancellation. E.g. max 24u before reservation start allowed to cancel.
    }
}
