<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TechnicianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    public function rules(): array
    {
        return [
            'technician_name' => ['required', 'string', 'max:150'],
            'expertise' => ['nullable', 'string', 'max:150'],
            'contact' => ['nullable', 'string', 'max:150'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'technician_name.required' => 'Nama teknisi wajib diisi.',
        ];
    }
}
