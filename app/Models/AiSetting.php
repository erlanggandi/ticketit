<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiSetting extends Model
{
    public const PROVIDER_GEMINI = 'gemini';

    public const PROVIDER_OPENAI = 'openai';

    public const PROVIDER_ANTHROPIC = 'anthropic';

    public const PROVIDER_OPENROUTER = 'openrouter';

    public const PROVIDER_CUSTOM = 'custom';

    /** @var list<string> */
    public const PROVIDERS = [
        self::PROVIDER_GEMINI,
        self::PROVIDER_OPENAI,
        self::PROVIDER_ANTHROPIC,
        self::PROVIDER_OPENROUTER,
        self::PROVIDER_CUSTOM,
    ];

    protected $fillable = [
        'provider',
        'api_key',
        'base_url',
        'model',
        'temperature',
        'max_tokens',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'api_key' => 'encrypted',
            'temperature' => 'float',
            'max_tokens' => 'integer',
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
            'provider' => self::PROVIDER_GEMINI,
            'api_key' => '',
            'base_url' => null,
            'model' => null,
            'temperature' => null,
            'max_tokens' => null,
        ]);
    }

    public function hasApiKey(): bool
    {
        return ! empty($this->api_key);
    }

    public function hasModel(): bool
    {
        return ! empty($this->model);
    }

    public function hasConnection(): bool
    {
        return $this->hasApiKey() && $this->hasModel();
    }
}
