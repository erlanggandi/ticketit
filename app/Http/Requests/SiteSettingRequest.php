<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SiteSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'app_name' => ['required', 'string', 'max:100'],
            'app_short_name' => ['required', 'string', 'max:20'],
            'tagline' => ['required', 'string', 'max:150'],
            'form_title' => ['required', 'string', 'max:200'],
            'form_description' => ['nullable', 'string', 'max:1000'],
            'footer_text' => ['required', 'string', 'max:200'],
            'logo' => ['nullable', 'image', 'mimes:png,ico,svg', 'max:512'],
            'favicon' => ['nullable', 'image', 'mimes:png,ico,svg', 'max:256'],
        ];
    }

    public function messages(): array
    {
        return [
            'app_name.required' => 'Nama aplikasi wajib diisi.',
            'app_short_name.required' => 'Nama singkat wajib diisi.',
            'tagline.required' => 'Tagline wajib diisi.',
            'form_title.required' => 'Judul form wajib diisi.',
            'footer_text.required' => 'Teks footer wajib diisi.',
            'logo.image' => 'Logo harus berupa file gambar.',
            'logo.mimes' => 'Logo harus format PNG, ICO, atau SVG.',
            'logo.max' => 'Ukuran logo maksimal 512 KB.',
            'favicon.image' => 'Favicon harus berupa file gambar.',
            'favicon.mimes' => 'Favicon harus format PNG, ICO, atau SVG.',
            'favicon.max' => 'Ukuran favicon maksimal 256 KB.',
        ];
    }
}
