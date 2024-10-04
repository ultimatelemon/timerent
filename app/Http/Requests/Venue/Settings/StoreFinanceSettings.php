<?php

namespace App\Http\Requests\Venue\Settings;

use Illuminate\Foundation\Http\FormRequest;

class StoreFinanceSettings extends FormRequest
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
            'payment_api_key' => 'nullable|sometimes',
            'payment_service_provider' => 'required|exists:payment_providers,text_id'
        ];
    }
}
