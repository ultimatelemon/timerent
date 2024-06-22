<?php

namespace App\Http\Requests\Authentication;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUser extends FormRequest
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
            'name' => 'required|string|min:2|max:48',
            'email' => 'required|string|email|unique:users,email|email:rfc,dns',
            'password' => 'required', 'string', Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised(),
        ];
    }
}
