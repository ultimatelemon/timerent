<?php

namespace App\Http\Requests\Venue;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreReservation extends FormRequest
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
            'subdomain' => 'required|exists:venues,subdomain',
            'name' => 'required|string|min:5|max:128',
            'email' => 'required|string|email|min:8|max:128',
            'phone_number' => 'required|numeric|digits:10',
            'date' => 'required',
            'comments' => 'nullable|string',
            'products' => 'nullable|array',
            'timeblocks' => 'required',
        ];
    }
}
