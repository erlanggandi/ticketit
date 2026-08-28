<?php

namespace App\Http\Requests;

use App\Models\TelegramSetting;
use Illuminate\Foundation\Http\FormRequest;

class TelegramSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    public function rules(): array
    {
        $hasSavedToken = (bool) TelegramSetting::current()?->bot_token;

        return [
            // Token boleh dikosongkan jika sudah pernah tersimpan (artinya tidak diubah).
            'bot_token' => [$hasSavedToken ? 'nullable' : 'required', 'string', 'max:255'],
            'chat_id' => ['required', 'string', 'max:100'],
            'message_template_new_ticket' => ['required', 'string', 'max:4000'],
            'message_template_sla_alert' => ['required', 'string', 'max:4000'],
        ];
    }

    public function messages(): array
    {
        return [
            'bot_token.required' => 'Bot Token wajib diisi.',
            'chat_id.required' => 'Chat ID wajib diisi.',
            'message_template_new_ticket.required' => 'Template pesan tiket baru wajib diisi.',
            'message_template_sla_alert.required' => 'Template pesan SLA alert wajib diisi.',
        ];
    }
}
