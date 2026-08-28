<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Aplikasi berpindah dari UTC ke zona server (APP_TIMEZONE=Asia/Singapore, +08:00).
 * Kolom timestamp bersifat naive (tanpa zona), jadi nilai historis yang tersimpan
 * sebagai instan UTC harus digeser +8 jam agar makna waktunya tetap benar setelah
 * aplikasi membaca nilainya sebagai waktu lokal.
 */
return new class extends Migration
{
    private const OFFSET_HOURS = 8;

    /** @var array<string, list<string>> */
    private const COLUMNS = [
        'users' => ['email_verified_at', 'created_at', 'updated_at'],
        'employees' => ['created_at', 'updated_at'],
        'categories' => ['created_at', 'updated_at'],
        'tickets' => ['sla_due_at', 'sla_alert_approaching_sent_at', 'sla_alert_breach_sent_at', 'resolved_at', 'closed_at', 'created_at', 'updated_at'],
        'sla_settings' => ['created_at', 'updated_at'],
        'telegram_settings' => ['created_at', 'updated_at'],
        'admin_notifications' => ['read_at', 'created_at', 'updated_at'],
        'audit_logs' => ['created_at'],
    ];

    public function up(): void
    {
        $this->shiftTimestamps(self::OFFSET_HOURS);
    }

    public function down(): void
    {
        $this->shiftTimestamps(-self::OFFSET_HOURS);
    }

    private function shiftTimestamps(int $hours): void
    {
        foreach (self::COLUMNS as $table => $columns) {
            foreach ($columns as $column) {
                DB::table($table)
                    ->whereNotNull($column)
                    ->update([$column => DB::raw($this->shiftExpression($column, $hours))]);
            }
        }
    }

    private function shiftExpression(string $column, int $hours): string
    {
        return match (DB::getDriverName()) {
            'sqlite' => "datetime({$column}, '{$hours} hours')",
            'pgsql' => "\"{$column}\" + interval '{$hours} hours'",
            default => "`{$column}` + interval {$hours} hour",
        };
    }
};
