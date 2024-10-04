<?php

namespace App\Http\Requests\Venue\Settings;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreReservationSettings extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'reservation_prefix' => 'nullable|sometimes|string|max:8',
            'cancellation_hours' => 'nullable|sometimes|numeric|between:0,168',
        ];
    }
}
