<?php

namespace App\Http\Controllers\Concerns;

trait InteractsWithCsv
{
    /**
     * Buang BOM UTF-8 yang disisipkan Excel/Sheets pada awal file.
     */
    protected function stripUtf8Bom(string $line): string
    {
        return str_starts_with($line, "\xEF\xBB\xBF") ? substr($line, 3) : $line;
    }

    /**
     * Pilih delimiter dari baris header: titik-koma (Excel ID) atau koma.
     */
    protected function detectDelimiter(string $line): string
    {
        return substr_count($line, ';') > substr_count($line, ',') ? ';' : ',';
    }

    /**
     * Huruf kecil; titik, underscore, dan strip diperlakukan sebagai spasi
     * agar varian penulisan kolom (mis. "Ticket Number" vs "ticket_number") cocok.
     */
    protected function normalizeKey(string $value): string
    {
        return preg_replace('/\s+/', ' ', str_replace(['.', '_', '-'], ' ', mb_strtolower(trim($value)))) ?? '';
    }
}
