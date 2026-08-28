<?php

namespace Database\Factories;

use App\Models\SiteSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

class SiteSettingFactory extends Factory
{
    protected $model = SiteSetting::class;

    public function definition(): array
    {
        return [
            'app_name' => 'IT Helpdesk',
            'app_short_name' => 'IT',
            'tagline' => 'Biro Dukungan Teknis',
            'form_title' => 'Lapor Masalah IT',
            'form_description' => 'Isi formulir ini untuk mencatat kendala yang Anda alami. Tim IT menangani sesuai tingkat urgensi.',
            'footer_text' => 'Formulir FRM-IT-001 · Rev.02',
            'logo_path' => null,
            'favicon_path' => null,
        ];
    }
}
