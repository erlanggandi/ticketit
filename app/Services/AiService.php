<?php

namespace App\Services;

use App\Models\AiSetting;
use App\Models\Ticket;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiService
{
    /**
     * @return array<string, string>
     */
    public function providers(): array
    {
        return [
            AiSetting::PROVIDER_GEMINI => 'Google Gemini',
            AiSetting::PROVIDER_OPENAI => 'OpenAI',
            AiSetting::PROVIDER_ANTHROPIC => 'Anthropic Claude',
            AiSetting::PROVIDER_OPENROUTER => 'OpenRouter',
            AiSetting::PROVIDER_CUSTOM => 'OpenAI Compatible',
        ];
    }

    public function testConnection(AiSetting $setting): array
    {
        $apiKey = trim((string) $setting->api_key);
        $model = trim((string) $setting->model);

        if ($apiKey === '' || $model === '') {
            return [
                'ok' => false,
                'message' => 'API Key dan model AI wajib diisi untuk melakukan test koneksi.',
                'models' => [],
            ];
        }

        try {
            $response = $this->performConnectionCheck($setting, $model);

            if (! $response['ok']) {
                return [
                    'ok' => false,
                    'message' => $response['message'],
                    'models' => [],
                ];
            }

            return [
                'ok' => true,
                'message' => 'Koneksi ke provider AI berhasil.',
                'models' => $response['models'],
            ];
        } catch (\Throwable $e) {
            Log::warning('AI connection test failed', ['error' => $e->getMessage()]);

            return [
                'ok' => false,
                'message' => 'Gagal melakukan test koneksi ke provider AI: '.$e->getMessage(),
                'models' => [],
            ];
        }
    }

    /**
     * @return array{ok: bool, message: string, models: array<int, string>}
     */
    public function performConnectionCheck(AiSetting $setting, string $model): array
    {
        $provider = (string) $setting->provider;
        $apiKey = trim((string) $setting->api_key);

        if ($provider === AiSetting::PROVIDER_GEMINI) {
            $baseUrl = (string) ($setting->base_url ?: 'https://generativelanguage.googleapis.com/v1beta');
            $response = Http::timeout(10)
                ->retry(2, 300, throw: false)
                ->get("{$baseUrl}/models", ['key' => $apiKey]);

            if (! $response->successful()) {
                throw new \RuntimeException('HTTP '.$response->status());
            }

            return [
                'ok' => true,
                'message' => 'Koneksi ke Google Gemini berhasil.',
                'models' => $this->extractGeminiModels($response),
            ];
        }

        if ($provider === AiSetting::PROVIDER_OPENAI || $provider === AiSetting::PROVIDER_OPENROUTER || $provider === AiSetting::PROVIDER_CUSTOM) {
            $baseUrl = (string) ($setting->base_url ?: ($provider === AiSetting::PROVIDER_OPENAI ? 'https://api.openai.com/v1' : 'https://openrouter.ai/api/v1'));
            $headers = [
                'Authorization' => "Bearer {$apiKey}",
                'Content-Type' => 'application/json',
            ];
            if ($provider === AiSetting::PROVIDER_OPENROUTER) {
                $headers['HTTP-Referer'] = config('app.url');
                $headers['X-Title'] = (string) config('app.name');
            }

            $response = Http::timeout(10)
                ->retry(2, 300, throw: false)
                ->withHeaders($headers)
                ->get("{$baseUrl}/models");

            if (! $response->successful()) {
                throw new \RuntimeException('HTTP '.$response->status());
            }

            $models = [];

            foreach ($response->json('data', []) as $item) {
                if (is_array($item) && isset($item['id']) && is_string($item['id'])) {
                    $models[] = $item['id'];
                }
            }

            return [
                'ok' => true,
                'message' => 'Koneksi ke provider AI berhasil.',
                'models' => array_values(array_unique($models)),
            ];
        }

        if ($provider === AiSetting::PROVIDER_ANTHROPIC) {
            $baseUrl = (string) ($setting->base_url ?: 'https://api.anthropic.com');
            $response = Http::timeout(10)
                ->retry(2, 300, throw: false)
                ->post("{$baseUrl}/v1/messages", [
                    'model' => $model,
                    'max_tokens' => 8,
                    'messages' => [['role' => 'user', 'content' => 'Ping']],
                ], [
                    'x-api-key' => $apiKey,
                    'anthropic-version' => '2023-06-01',
                    'content-type' => 'application/json',
                ]);

            if (! $response->successful()) {
                throw new \RuntimeException('HTTP '.$response->status());
            }

            return [
                'ok' => true,
                'message' => 'Koneksi ke Anthropic Claude berhasil.',
                'models' => [
                    'claude-3-5-sonnet-20241022',
                    'claude-3-haiku-20240307',
                ],
            ];
        }

        throw new \RuntimeException('Provider AI tidak didukung.');
    }

    /**
     * @return array<int, string>
     */
    private function extractGeminiModels($response): array
    {
        $models = [];

        foreach ($response->json('data', []) as $item) {
            if (! is_array($item) || ! isset($item['name']) || ! is_string($item['name'])) {
                continue;
            }

            if (! in_array('generateContent', $item['supportedGenerationMethods'] ?? [], true)) {
                continue;
            }

            $models[] = trim(str_replace('models/', '', $item['name']));
        }

        return array_values(array_unique($models));
    }

    public function diagnose(Ticket $ticket, AiSetting $setting): string
    {
        $prompt = $this->buildPrompt($ticket);

        return $this->chatCompletion($setting, $prompt, (int) ($setting->max_tokens ?? 700));
    }

    public function chatCompletion(AiSetting $setting, string $prompt, ?int $maxTokens = null): string
    {
        $provider = (string) $setting->provider;
        $apiKey = trim((string) $setting->api_key);
        $model = trim((string) $setting->model);

        if ($apiKey === '' || $model === '') {
            throw new \RuntimeException('API Key dan model AI belum dikonfigurasi.');
        }

        if ($provider === AiSetting::PROVIDER_GEMINI) {
            $baseUrl = (string) ($setting->base_url ?: 'https://generativelanguage.googleapis.com/v1beta');
            $response = Http::timeout(20)
                ->retry(2, 300, throw: false)
                ->post("{$baseUrl}/models/{$model}:generateContent?key={$apiKey}", [
                    'contents' => [
                        ['parts' => [['text' => $prompt]]],
                    ],
                ]);

            if (! $response->successful()) {
                throw new \RuntimeException('HTTP '.$response->status());
            }

            $text = trim((string) ($response->json('candidates.0.content.parts.0.text') ?? ''));

            if ($text === '') {
                throw new \RuntimeException('Respons AI Gemini tidak valid.');
            }

            return $text;
        }

        if ($provider === AiSetting::PROVIDER_ANTHROPIC) {
            $baseUrl = (string) ($setting->base_url ?: 'https://api.anthropic.com');
            $response = Http::timeout(20)
                ->retry(2, 300, throw: false)
                ->post("{$baseUrl}/v1/messages", [
                    'model' => $model,
                    'max_tokens' => max($maxTokens ?? 512, 1),
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt],
                    ],
                ], [
                    'x-api-key' => $apiKey,
                    'anthropic-version' => '2023-06-01',
                    'content-type' => 'application/json',
                ]);

            if (! $response->successful()) {
                throw new \RuntimeException('HTTP '.$response->status());
            }

            $text = trim((string) ($response->json('content.0.text') ?? ''));

            if ($text === '') {
                throw new \RuntimeException('Respons AI Anthropic tidak valid.');
            }

            return $text;
        }

        $baseUrl = (string) ($setting->base_url ?: ($provider === AiSetting::PROVIDER_OPENAI ? 'https://api.openai.com/v1' : 'https://openrouter.ai/api/v1'));
        $headers = [
            'Authorization' => "Bearer {$apiKey}",
            'Content-Type' => 'application/json',
        ];
        if ($provider === AiSetting::PROVIDER_OPENROUTER) {
            $headers['HTTP-Referer'] = config('app.url');
            $headers['X-Title'] = (string) config('app.name');
        }

        $response = Http::timeout(30)
            ->retry(2, 300, throw: false)
            ->withHeaders($headers)
            ->post("{$baseUrl}/chat/completions", [
                'model' => $model,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
                'max_tokens' => max($maxTokens ?? 512, 1),
            ]);

        if (! $response->successful()) {
            throw new \RuntimeException('HTTP '.$response->status());
        }

        $text = trim((string) ($response->json('choices.0.message.content') ?? ''));

        if ($text === '') {
            throw new \RuntimeException('Respons AI tidak valid.');
        }

        return $text;
    }

    private function buildPrompt(Ticket $ticket): string
    {
        $prompt = 'Anda adalah teknisi IT helpdesk. Berikan diagnosis singkat dan praktis untuk tiket berikut dalam Bahasa Indonesia. '
            .'Batasi maksimal 3 kalimat, tanpa markdown.'."\n"
            .'Nomor Tiket: '.$ticket->ticket_number."\n"
            .'Nama Pelapor: '.$ticket->requester_name."\n"
            .'Departemen: '.($ticket->department ?? '-')."\n"
            .'Kategori: '.($ticket->category?->category_name ?? '-')."\n"
            .'Prioritas: '.$ticket->priority."\n"
            .'Status: '.$ticket->status."\n"
            .'SLA Due: '.optional($ticket->sla_due_at)->format('d M Y H:i')."\n"
            .'Deskripsi: '.($ticket->description ?? '');

        return trim($prompt);
    }
}
