<?php

namespace App\Http\Requests\Venue\Settings;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreGeneralSettings extends FormRequest
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
            'name' => 'required|min:1|max:255',
            'address' => 'required|min:1|max:255',
            'postal_code' => 'required|min:1|max:7',
            'city' => 'required|min:1|max:255',
            'email' => 'required|email:rfc,dns',
            'phone_number' => 'required|numeric|digits_between:10,12',
            'phone_number_support' => 'required|numeric|digits_between:10,12',
            'coc_number' => 'required|numeric|digits:8',
            'tax_number' => 'required|string|min:14|max:14',
        ];
    }
}
