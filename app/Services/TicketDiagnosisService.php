<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\TicketDiagnosis;

class TicketDiagnosisService
{
    /**
     * Analyze a ticket and return diagnosis results.
     *
     * @return array{0: bool, diagnosis: string, confidence: int, method: string}
     */
    public function analyze(Ticket $ticket): array
    {
        $rules = $this->buildRules($ticket);
        $result = $this->applyRules($rules, $ticket);
        $confidence = $this->calculateConfidence($rules, $ticket);

        // Save diagnosis to database
        $analyzedBy = auth()->id() ?? null;

        $diagnosis = TicketDiagnosis::updateOrCreate(
            ['ticket_id' => $ticket->id],
            [
                'diagnosis_result' => $result,
                'confidence_score' => $confidence,
                'analysis_method' => 'rule-based',
                'analyzed_by' => $analyzedBy,
            ]
        );

        return [
            'success' => true,
            'diagnosis' => $result,
            'confidence' => $confidence,
            'method' => 'rule-based',
            'saved' => true,
        ];
    }

    /**
     * Build rules based on ticket properties.
     */
    protected function buildRules(Ticket $ticket): array
    {
        $category = $ticket->category?->category_name ?? '';
        $description = $ticket->description ?? '';
        $priority = $ticket->priority;
        $isOverdue = $ticket->isOverdue();

        $keywords = $this->extractKeywords($description);

        return [
            'category' => $category,
            'description' => $description,
            'priority' => $priority,
            'isOverdue' => $isOverdue,
            'keywords' => $keywords,
            'ticketNumber' => $ticket->ticket_number,
        ];
    }

    /**
     * Extract simple keywords from description (lowercase, split by spaces).
     */
    protected function extractKeywords(string $description): array
    {
        if (empty($description)) {
            return [];
        }

        $lower = strtolower($description);
        // Remove common Indonesian stop words
        $stopWords = ['yang', 'dan', 'untuk', 'dengan', 'pada', 'itu', 'ini', 'karena', 'oleh', 'bisa', 'sudah', 'saya', 'kami', 'anda'];
        $words = array_diff(explode(' ', $lower), $stopWords);

        return array_values(array_filter($words, function ($w) {
            return strlen($w) > 2;
        }));
    }

    /**
     * Apply rule-based diagnosis and return result string.
     */
    protected function applyRules(array $rules, Ticket $ticket): string
    {
        $category = $rules['category'];
        $priority = $rules['priority'];
        $isOverdue = $rules['isOverdue'];
        $keywords = $rules['keywords'];
        $ticketNumber = $rules['ticketNumber'];

        $diagnosisParts = [];

        // Rule: Priority enhancement
        $priorityTags = [
            'Critical' => 'Segera tanggapi, escalate ke tim senior',
            'High' => 'Prioritaskan dalam 24 jam',
            'Medium' => 'Tangani sesuai jadwal',
            'Low' => 'Tangani saat sengaja',
        ];

        if (isset($priorityTags[$priority])) {
            $diagnosisParts[] = $priorityTags[$priority];
        }

        // Rule: SLA warning
        if ($isOverdue) {
            $diagnosisParts[] = '⚠️ SLA sudah lewat, tanggapi segera';
        }

        // Rule: Category + Keyword based diagnosis
        $diagnosisText = $this->diagnoseByCategoryAndKeywords($category, $keywords, $priority);

        if (! empty($diagnosisText)) {
            $diagnosisParts[] = $diagnosisText;
        }

        // Rule: Description length check
        $descLen = strlen($ticket->description ?? '');
        if ($descLen < 20) {
            $diagnosisParts[] = '⚠️ Deskripsi pendek (<20 karakter), periksa detail untuk akurasi diagnosis yang lebih baik';
        }

        // Build final result
        $result = empty($diagnosisParts)
            ? 'Tidak ditemukan pola spesifik dalam deskripsi tiket ini. Periksa kategori dan deskripsi untuk analisis lebih lanjut.'
            : implode(' | ', $diagnosisParts);

        return $result;
    }

    /**
     * Diagnose based on category and keywords.
     */
    protected function diagnoseByCategoryAndKeywords(string $category, array $keywords, string $priority): string
    {
        $category = strtolower($category ?? '');
        $keywordStr = implode(' ', $keywords);

        // Hardware rules
        if (in_array($category, ['hardware', 'hardware perangkat', 'perangkat'])) {
            if (in_array('power', $keywords) || in_array('listrik', $keywords)) {
                return 'Mungkin power supply atau koneksi listrik bermasalah. Cek kabel power, swap outlet, atau hubungkan ke teknisi hardware.';
            }
            if (in_array('laptop', $keywords) || in_array('pc', $keywords)) {
                return 'Masalah perangkat keras laptop/PC. Cek RAM, hard drive, atau komponen internal. Sarankan dicek oleh teknisi.';
            }
            if (inarray('cetak', $keywords) || in_array('printer', $keywords)) {
                return 'Masalah printer. Cek kertas terjangkal, ink/toner habis, atau koneksi USB/jaringan. Lakukan cleaning print head.';
            }

            return 'Masalah perangkat keras. Periksa koneksi, listrik, dan komponen fisik. Sarankan untuk diverifikasi oleh teknisi.';
        }

        // Software rules
        if (in_array($category, ['software', 'software aplikasi', 'aplikasi'])) {
            if (in_array('lambat', $keywords) || in_array('slow', $keywords)) {
                return 'Aplikasi terlambat merespons. Cek resource CPU/memory, tutup aplikasi yang tidak diperlukan, atau perbarui ke versi terbaru.';
            }
            if (in_array('error', $keywords) || in_array('crash', $keywords)) {
                return 'Aplikasi mengalami error/crash. Catat pesan error tersebut, perbarui aplikasi, atau lakukan reinstallasi.';
            }
            if (in_array('login', $keywords) || in_array('akun', $keywords)) {
                return 'Masalah akses/login akun. Cek kredensial, reset password, atau hubungi administrator sistem.';
            }

            return 'Masalah aplikasi. Cek log error, perbarui versi, atau hubungi tim teknologi informasi.';
        }

        // Network rules
        if (in_array($category, ['network', 'jaringan', 'internet'])) {
            if (in_array('wifi', $keywords) || in_array('wireless', $keywords)) {
                return 'Masalah koneksi WiFi. Cek sinyal router, restart perangkat, atau geser channel jaringan. Cek juga MAC address yang terblokir.';
            }
            if (in_array('internet', $keywords) || in_array('online', $keywords)) {
                return 'Masalah koneksi internet. Restart modem/router, cek paket data, atau hubungi provider layanan internet.';
            }

            return 'Masalah jaringan. Cek kabel koneksi, setting router, atau hubungi tim infrastruktur.';
        }

        // Account rules
        if (in_array($category, ['account', 'akun', 'user'])) {
            if (in_array('password', $keywords) || in_array('pass', $keywords)) {
                return 'Masalah password. Reset password akun, cek ketelitian input, atau atur kebijakan password baru.';
            }
            if (in_array('hak', $keywords) || in_array('access', $keywords)) {
                return 'Masalah hak akses. Periksa peran user (role), permission yang dimiliki, atau ajukan permintaan akses baru.';
            }

            return 'Masalah akun pengguna. Cek kredensial, hak akses, atau hubungi administrator.';
        }

        // Default: generic based on keywords
        $genericTips = [
            'tidak bisa' => 'Masalah penggunaan sistem. Cek kembali langkah-langkah yang diambil, atau hubungi support untuk guidance langkah selanjutnya.',
            'mengalami' => 'User mengalami kendala. Periksa inputan, koneksi, atau konfigurasi yang terkait.',
            'tolak' => 'Aksi ditolak oleh sistem. Cek izin, validasi input, atau konfigurasi yang berkaitan.',
        ];

        foreach ($genericTips as $keyword => $tip) {
            if (in_array($keyword, $keywords)) {
                return $tip;
            }
        }

        return '';
    }

    /**
     * Calculate confidence score (0-100) based on how many rules matched.
     */
    protected function calculateConfidence(array $rules, Ticket $ticket): int
    {
        $score = 0;
        $max = 100;

        $category = $rules['category'];
        $keywords = $rules['keywords'];
        $priority = $rules['priority'];
        $isOverdue = $rules['isOverdue'];

        // Base score
        $score += 20;

        // Category match adds confidence
        if (! empty($category)) {
            $score += 20;
        }

        // Priority influence
        if (in_array($priority, ['Critical', 'High'])) {
            $score += 10;
        }

        // Overdue adds higher confidence for urgency
        if ($isOverdue) {
            $score += 15;
        }

        // Keyword richness
        if (count($keywords) >= 3) {
            $score += 15;
        } elseif (count($keywords) >= 1) {
            $score += 10;
        }

        // Cap at 100
        return min($score, $max);
    }
}
