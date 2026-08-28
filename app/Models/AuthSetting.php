<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuthSetting extends Model
{
    use HasFactory;

    public const FIELD_PASSWORD_MIN_LENGTH = 'password_min_length';

    public const FIELD_PASSWORD_REQUIRE_UPPERCASE = 'password_require_uppercase';

    public const FIELD_PASSWORD_REQUIRE_LOWERCASE = 'password_require_lowercase';

    public const FIELD_PASSWORD_REQUIRE_NUMBER = 'password_require_number';

    public const FIELD_PASSWORD_REQUIRE_SYMBOL = 'password_require_symbol';

    public const FIELD_LOGIN_ATTEMPTS_BEFORE_LOCKOUT = 'login_attempts_before_lockout';

    public const FIELD_LOCKOUT_DURATION_MINUTES = 'lockout_duration_minutes';

    public const FIELD_SESSION_TIMEOUT_MINUTES = 'session_timeout_minutes';

    public const FIELD_REMEMBER_ME_DURATION_MINUTES = 'remember_me_duration_minutes';

    /** @var list<string> */
    public const FIELDS = [
        self::FIELD_PASSWORD_MIN_LENGTH,
        self::FIELD_PASSWORD_REQUIRE_UPPERCASE,
        self::FIELD_PASSWORD_REQUIRE_LOWERCASE,
        self::FIELD_PASSWORD_REQUIRE_NUMBER,
        self::FIELD_PASSWORD_REQUIRE_SYMBOL,
        self::FIELD_LOGIN_ATTEMPTS_BEFORE_LOCKOUT,
        self::FIELD_LOCKOUT_DURATION_MINUTES,
        self::FIELD_SESSION_TIMEOUT_MINUTES,
        self::FIELD_REMEMBER_ME_DURATION_MINUTES,
    ];

    protected $fillable = [
        'password_min_length',
        'password_require_uppercase',
        'password_require_lowercase',
        'password_require_number',
        'password_require_symbol',
        'login_attempts_before_lockout',
        'lockout_duration_minutes',
        'session_timeout_minutes',
        'remember_me_duration_minutes',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'password_min_length' => 'integer',
            'password_require_uppercase' => 'boolean',
            'password_require_lowercase' => 'boolean',
            'password_require_number' => 'boolean',
            'password_require_symbol' => 'boolean',
            'login_attempts_before_lockout' => 'integer',
            'lockout_duration_minutes' => 'integer',
            'session_timeout_minutes' => 'integer',
            'remember_me_duration_minutes' => 'integer',
        ];
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public static function current(): ?self
    {
        return static::query()->first();
    }
}
