<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiteSetting extends Model
{
    use HasFactory;

    public const FIELD_APP_NAME = 'app_name';

    public const FIELD_APP_SHORT_NAME = 'app_short_name';

    public const FIELD_TAGLINE = 'tagline';

    public const FIELD_FORM_TITLE = 'form_title';

    public const FIELD_FORM_DESCRIPTION = 'form_description';

    public const FIELD_FOOTER_TEXT = 'footer_text';

    public const FIELD_LOGO_PATH = 'logo_path';

    public const FIELD_FAVICON_PATH = 'favicon_path';

    /** @var list<string> */
    public const FIELDS = [
        self::FIELD_APP_NAME,
        self::FIELD_APP_SHORT_NAME,
        self::FIELD_TAGLINE,
        self::FIELD_FORM_TITLE,
        self::FIELD_FORM_DESCRIPTION,
        self::FIELD_FOOTER_TEXT,
        self::FIELD_LOGO_PATH,
        self::FIELD_FAVICON_PATH,
    ];

    protected $fillable = [
        'app_name',
        'app_short_name',
        'tagline',
        'form_title',
        'form_description',
        'footer_text',
        'logo_path',
        'favicon_path',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'form_description' => 'string',
        ];
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public static function current(): ?static
    {
        return static::query()->first();
    }

    public static function currentOrCreate(): static
    {
        return static::query()->firstOrCreate([], [
            'app_name' => config('app.name'),
            'app_short_name' => 'IT',
            'tagline' => 'Biro Dukungan Teknis',
            'form_title' => 'Lapor Masalah IT',
            'form_description' => 'Isi formulir ini untuk mencatat kendala yang Anda alami. Tim IT menangani sesuai tingkat urgensi, dan Anda dapat memantau progresnya lewat nomor tiket — tanpa perlu login.',
            'footer_text' => 'Formulir FRM-IT-001 · Rev.02',
        ]);
    }
}
