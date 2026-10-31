<?php

namespace App\Http\Requests;

use App\Models\AiSetting;
use Illuminate\Foundation\Http\FormRequest;

class AiSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    public function rules(): array
    {
        return [
            'provider' => ['required', 'string', 'in:'.implode(',', AiSetting::PROVIDERS)],
            'api_key' => ['nullable', 'string', 'max:500'],
            'base_url' => ['nullable', 'string', 'max:500'],
            'model' => ['nullable', 'string', 'max:100'],
            'temperature' => ['nullable', 'numeric', 'min:0', 'max:2'],
            'max_tokens' => ['nullable', 'integer', 'min:1', 'max:8192'],
        ];
    }

    public function messages(): array
    {
        return [
            'provider.required' => 'Provider AI wajib dipilih.',
            'provider.in' => 'Provider AI tidak valid.',
            'api_key.max' => 'API Key terlalu panjang.',
            'base_url.max' => 'URL base tidak valid.',
            'model.max' => 'Nama model tidak valid.',
            'temperature.min' => 'Suhu harus antara 0 dan 2.',
            'temperature.max' => 'Suhu harus antara 0 dan 2.',
            'max_tokens.min' => 'Max token tidak valid.',
            'max_tokens.max' => 'Max token terlalu besar.',
        ];
    }
}
