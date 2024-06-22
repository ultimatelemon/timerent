<?php

namespace App\Http\Requests\Venue;

use Illuminate\Foundation\Http\FormRequest;

class StoreTemplate extends FormRequest
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
            'interval' => 'required|numeric|min:1',
            'template' => 'required',
            'price' => 'required'
        ];
    }
}
