<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TelegramSettingRequest;
use App\Models\TelegramSetting;
use App\Services\AuditLogger;
use App\Services\TelegramService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TelegramSettingController extends Controller
{
    public function show(): View
    {
        $setting = TelegramSetting::query()->first();

        return view('admin.telegram-settings.show', [
            'setting' => $setting,
            'variables' => TelegramSetting::TEMPLATE_VARIABLES,
            'defaultTemplateNewTicket' => TelegramSetting::DEFAULT_TEMPLATE_NEW_TICKET,
            'defaultTemplateSlaAlert' => TelegramSetting::DEFAULT_TEMPLATE_SLA_ALERT,
        ]);
    }

    public function update(TelegramSettingRequest $request, AuditLogger $audit): RedirectResponse
    {
        $setting = TelegramSetting::query()->firstOrNew();
        $validated = $request->validated();

        $old = [
            'chat_id' => $setting->chat_id,
            'message_template_new_ticket' => $setting->message_template_new_ticket,
            'message_template_sla_alert' => $setting->message_template_sla_alert,
            'bot_token_changed' => false,
        ];

        if (! empty($validated['bot_token'])) {
            $old['bot_token_changed'] = true;
            $setting->bot_token = trim($validated['bot_token']);
        }

        $setting->fill([
            'chat_id' => trim($validated['chat_id']),
            'message_template_new_ticket' => $validated['message_template_new_ticket'],
            'message_template_sla_alert' => $validated['message_template_sla_alert'],
            'updated_by' => $request->user()?->getAuthIdentifier(),
        ]);

        $setting->save();
        $audit->record($setting, 'updated', $old, ['telegram_settings_saved' => true]);

        return back()->with('success', 'Pengaturan Telegram berhasil disimpan.');
    }

    public function test(TelegramService $telegram): JsonResponse
    {
        $setting = TelegramSetting::current();

        if (! $setting) {
            return response()->json([
                'ok' => false,
                'message' => 'Pengaturan belum tersimpan. Simpan terlebih dahulu.',
            ]);
        }

        [$ok, $message] = $telegram->send(
            $setting,
            "Test pesan dari IT Helpdesk Ticketing System.\n\nJika Anda menerima pesan ini, konfigurasi Telegram sudah benar."
        );

        return response()->json(['ok' => $ok, 'message' => $message]);
    }
}
