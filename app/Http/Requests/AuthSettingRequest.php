<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AuthSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $request->user()->can('manage authentication settings');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'password_min_length' => 'sometimes|integer|min:4|max:72',
            'password_require_uppercase' => 'sometimes|boolean',
            'password_require_lowercase' => 'sometimes|boolean',
            'password_require_number' => 'sometimes|boolean',
            'password_require_symbol' => 'sometimes|boolean',
            'login_attempts_before_lockout' => 'sometimes|integer|min:1',
            'lockout_duration_minutes' => 'sometimes|integer|min:1',
            'session_timeout_minutes' => 'sometimes|integer|min:1',
            'remember_me_duration_minutes' => 'sometimes|integer|min:1',
        ];
    }
}
