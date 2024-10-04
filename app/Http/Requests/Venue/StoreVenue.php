<?php

namespace App\Http\Requests\Venue;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StoreVenue extends FormRequest
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
            'name' => 'required|string|max:32',
            'description' => 'nullable|string|max:200',
            'address' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'city' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'coc_number' => 'nullable|string',
            'tax_number' => 'nullable|string',
            'bank_number' => 'nullable|string',

            'plan_id' => 'required|exists:plans,id',
            'subdomain' => 'required|string|unique:venues,subdomain|lowercase|max:32|regex:/^[a-z\-]+$/',

            'avatar_id' => 'nullable|uuid|exists:files,id',
            'cover_id' => 'nullable|uuid|exists:files,id',
            'receipt_logo_id' => 'nullable|uuid|exists:files,id',
        ];
    }
}
