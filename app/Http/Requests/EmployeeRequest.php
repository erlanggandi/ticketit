<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    public function rules(): array
    {
        return [
            'employee_name' => ['required', 'string', 'max:150'],
            'department' => ['nullable', 'string', 'max:100'],
            'contact' => ['nullable', 'string', 'max:150'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'employee_name.required' => 'Nama karyawan wajib diisi.',
        ];
    }
}
