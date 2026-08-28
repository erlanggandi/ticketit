<?php

namespace App\Http\Requests;

use App\Models\Ticket;
use Illuminate\Foundation\Http\FormRequest;

class SlaSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    public function rules(): array
    {
        $rules = [];

        foreach (Ticket::PRIORITIES as $priority) {
            $rules['durations.'.strtolower($priority)] = ['required', 'integer', 'min:1', 'max:8760'];
        }

        return $rules;
    }

    /**
     * @return array<string, int>
     */
    public function durationsByPriority(): array
    {
        return collect(Ticket::PRIORITIES)
            ->mapWithKeys(fn ($priority) => [
                $priority => (int) $this->input('durations.'.strtolower($priority)),
            ])
            ->all();
    }

    public function messages(): array
    {
        return [
            'required' => 'Durasi wajib diisi.',
            'integer' => 'Durasi harus berupa angka.',
            'min' => 'Durasi minimal 1 jam.',
            'max' => 'Durasi maksimal 8760 jam (1 tahun).',
        ];
    }
}
