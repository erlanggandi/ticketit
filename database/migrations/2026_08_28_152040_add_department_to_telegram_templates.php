<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('telegram_settings')->get()->each(function ($row): void {
            $updates = [];

            foreach (['message_template_new_ticket', 'message_template_sla_alert'] as $column) {
                $value = (string) ($row->{$column} ?? '');

                if ($value === '' || str_contains($value, '{department}')) {
                    continue;
                }

                $updates[$column] = $this->insertDepartmentLine($value);
            }

            if ($updates !== []) {
                DB::table('telegram_settings')->where('id', $row->id)->update($updates);
            }
        });
    }

    public function down(): void
    {
        // Data migration — no schema to revert.
    }

    public static function insertDepartmentLine(string $template): string
    {
        if (str_contains($template, '{department}')) {
            return $template;
        }

        $replaced = preg_replace(
            '/(^.*Requester:.*$)/m',
            "$1\nDepartemen: {department}",
            $template,
            1
        );

        if (is_string($replaced) && $replaced !== $template) {
            return $replaced;
        }

        return rtrim($template)."\nDepartemen: {department}";
    }
};
