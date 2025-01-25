<?php

namespace App\Http\Requests\Venue;

use Illuminate\Foundation\Http\FormRequest;

class StoreProduct extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:32',
            'description' => 'string|nullable|max:64',
            'price' => 'min:1|max:9999999|integer|required',
            'is_active' => 'required|boolean',
            'tax_percentage' => 'required|integer|in:0,9,21',
            'max_per_day' => 'min:0|max:9999|required|integer',
            'max_per_reservation' => 'min:0|max:50|required|integer',
            'price_per_timeblock' => 'required|boolean',
            'units' => 'nullable',
            'units.*' => 'string|distinct|exists:units,id',
        ];
    }
}
