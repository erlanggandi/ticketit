<?php

namespace App\Http\Requests;

use App\Models\Ticket;
use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'requester_name' => ['required', 'string', 'min:2', 'max:150'],
            'employee_id' => ['nullable', 'integer', 'exists:employees,id,is_active,1'],
            'category_id' => ['required', 'integer', 'exists:categories,id,is_active,1'],
            'priority' => ['required', 'string', 'in:'.implode(',', Ticket::PRIORITIES)],
            'description' => ['required', 'string', 'min:10', 'max:5000'],
        ];
    }

    public function messages(): array
    {
        return [
            'requester_name.required' => 'Nama wajib diisi.',
            'requester_name.min' => 'Nama minimal 2 karakter.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists' => 'Kategori tidak valid.',
            'priority.required' => 'Prioritas wajib dipilih.',
            'priority.in' => 'Prioritas tidak valid.',
            'description.required' => 'Deskripsi masalah wajib diisi.',
            'description.min' => 'Deskripsi minimal 10 karakter.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'requester_name' => trim((string) $this->input('requester_name')),
            'employee_id' => $this->input('employee_id') ?: null,
        ]);
    }
}
