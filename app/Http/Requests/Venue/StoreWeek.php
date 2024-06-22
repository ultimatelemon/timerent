<?php

namespace App\Http\Requests\Venue;

use Illuminate\Foundation\Http\FormRequest;

class StoreWeek extends FormRequest
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
            'year' => 'required|numeric|digits:4',
            'week' => 'required|numeric|digits_between:1,52',
            'unit_id' => 'required|exists:units,id',
            'template_id' => 'sometimes|nullable|exists:templates,id',
        ];
    }
}
