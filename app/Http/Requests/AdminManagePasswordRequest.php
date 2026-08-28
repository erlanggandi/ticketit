<?php

namespace App\Http\Requests;

use App\Models\AuthSetting;
use Illuminate\Foundation\Http\FormRequest;

class AdminManagePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $minLength = AuthSetting::current()?->password_min_length ?? 8;

        return [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', "min:{$minLength}", 'max:72', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' => 'Password lama wajib diisi.',
            'current_password.current_password' => 'Password lama tidak cocok.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal :min karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ];
    }
}
