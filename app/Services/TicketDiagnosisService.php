<?php

namespace App\Services;

use App\Models\AiSetting;
use App\Models\Ticket;

class TicketDiagnosisService
{
    public function __construct(private readonly AiService $aiService) {}

    /**
     * @return array{success: bool, diagnosis: string, confidence: int, method: string, saved: bool, message: string}
     */
    public function analyze(Ticket $ticket): array
    {
        $setting = AiSetting::query()->first();

        if ($setting === null || ! $setting->hasConnection()) {
            return [
                'success' => false,
                'diagnosis' => 'Konfigurasi provider AI belum lengkap. Silakan atur API Key dan model AI terlebih dahulu.',
                'confidence' => 0,
                'method' => 'ai',
                'saved' => false,
                'message' => 'Konfigurasi provider AI belum lengkap.',
            ];
        }

        try {
            $diagnosis = $this->aiService->diagnose($ticket, $setting);

            return [
                'success' => true,
                'diagnosis' => $diagnosis,
                'confidence' => 100,
                'method' => 'ai',
                'saved' => false,
                'message' => 'Diagnosis berhasil dihasilkan oleh AI.',
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'diagnosis' => 'Gagal memanggil provider AI: '.$e->getMessage(),
                'confidence' => 0,
                'method' => 'ai',
                'saved' => false,
                'message' => 'Gagal memanggil provider AI.',
            ];
        }
    }
}
