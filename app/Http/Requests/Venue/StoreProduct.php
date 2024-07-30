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
            'name' => 'required|string',
            'description' => 'string|nullable',
            'price' => 'integer|required',
            'is_active' => 'required|boolean',
            'tax_percentage' => 'required|integer|in:0,9,21',
            'max_per_day' => 'required|integer|min:0',
            'price_per_timeblock' => 'required|boolean',
        ];
    }
}
