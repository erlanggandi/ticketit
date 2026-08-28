@extends('layouts.admin')

@section('title', 'Alert Telegram')
@section('page_title', 'Pengaturan Alert Telegram')
@section('page_subtitle', 'Notifikasi tiket baru & alert SLA via Bot Telegram')

@section('content')
    <div class="grid max-w-5xl grid-cols-1 gap-6 xl:grid-cols-3">
        <div class="space-y-6 xl:col-span-2">
            <x-card title="Konfigurasi Bot">
                <form method="POST" action="{{ route('admin.telegram-settings.update') }}" data-disable-on-submit class="space-y-5">
                    @csrf
                    @method('PUT')

                    <x-input name="bot_token" type="password"
                             label="Bot Token"
                             :required="!$setting?->bot_token"
                             placeholder="{{ $setting?->bot_token ? 'Biarkan kosong jika tidak ingin mengubah' : '1234567890:AAxxxx...' }}"
                             hint="{{ $setting?->bot_token ? 'Token tersimpan: '.$setting->maskedBotToken() : 'Dibuat via @BotFather di Telegram.' }}"
                             autocomplete="new-password" />

                    <x-input name="chat_id" label="Chat / Group ID" :required="true"
                             :value="$setting?->chat_id"
                             placeholder="mis. -1001234567890"
                             hint="ID chat tujuan notifikasi (bisa grup IT)." />

                    <div class="rounded-[5px] border border-dashed border-rule p-4">
                        <div class="mb-1.5 flex flex-wrap items-center justify-between gap-2">
                            <label for="message_template_new_ticket" class="font-mono text-[11px] font-semibold uppercase tracking-[0.12em] text-ink">Template Pesan — Tiket Baru</label>
                            <button type="button" onclick="insertVars('message_template_new_ticket')" class="font-mono text-[10px] font-semibold uppercase tracking-wide text-stamp-700 hover:underline">Sisipkan variabel</button>
                        </div>
                        <textarea name="message_template_new_ticket" id="message_template_new_ticket" rows="7"
                                  class="field font-mono text-xs leading-relaxed">{{ old('message_template_new_ticket', $setting?->message_template_new_ticket ?? $defaultTemplateNewTicket) }}</textarea>
                        @error('message_template_new_ticket')<p class="mt-1 font-mono text-[11px] uppercase tracking-wide text-critical-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="rounded-[5px] border border-dashed border-rule p-4">
                        <div class="mb-1.5 flex flex-wrap items-center justify-between gap-2">
                            <label for="message_template_sla_alert" class="font-mono text-[11px] font-semibold uppercase tracking-[0.12em] text-ink">Template Pesan — Alert SLA</label>
                            <button type="button" onclick="insertVars('message_template_sla_alert')" class="font-mono text-[10px] font-semibold uppercase tracking-wide text-stamp-700 hover:underline">Sisipkan variabel</button>
                        </div>
                        <textarea name="message_template_sla_alert" id="message_template_sla_alert" rows="7"
                                  class="field font-mono text-xs leading-relaxed">{{ old('message_template_sla_alert', $setting?->message_template_sla_alert ?? $defaultTemplateSlaAlert) }}</textarea>
                        @error('message_template_sla_alert')<p class="mt-1 font-mono text-[11px] uppercase tracking-wide text-critical-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="rounded-[4px] border border-stamp-200 bg-stamp-50 p-3.5 text-xs leading-relaxed text-stamp-900">
                        Variabel yang didukung:
                        @foreach ($variables as $variable)
                            <code class="mx-0.5 rounded-[3px] bg-sheet px-1.5 py-0.5 font-mono text-[11px] text-stamp-700">{{ $variable }}</code>
                        @endforeach
                    </div>

                    <x-button type="submit">Simpan Pengaturan</x-button>
                </form>
            </x-card>
        </div>

        <div class="space-y-6">
            <x-card title="Test Kirim Pesan">
                <p class="mb-4 text-sm leading-relaxed text-muted">
                    Kirim pesan percobaan ke Chat ID yang tersimpan untuk memastikan konfigurasi sudah benar.
                </p>
                <button type="button" id="test-send-btn"
                        class="btn btn-secondary w-full">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                    </svg>
                    Test Kirim Pesan
                </button>

                <div id="test-result" class="mt-4 hidden rounded-[5px] border-l-4 px-3 py-2.5 text-sm"></div>

                @if (!$setting?->bot_token)
                    <p class="mt-3 font-mono text-[11px] uppercase tracking-wide text-signal-600">Simpan Bot Token terlebih dahulu sebelum melakukan test.</p>
                @endif
            </x-card>

            <x-card title="Cara Setup">
                <ol class="list-decimal space-y-2 pl-4 text-sm leading-relaxed text-muted marker:font-mono marker:text-faint">
                    <li>Buka <strong class="text-ink">@BotFather</strong> di Telegram, buat bot dengan <code class="rounded-[3px] bg-paper px-1 py-0.5 font-mono text-xs">/newbot</code>, salin token yang diberikan.</li>
                    <li>Tambahkan bot ke grup IT (atau chat langsung), kirim satu pesan apa pun.</li>
                    <li>Ambil Chat ID grup via <code class="rounded-[3px] bg-paper px-1 py-0.5 font-mono text-xs">@userinfobot</code> atau API getUpdates.</li>
                    <li>Tempel Bot Token &amp; Chat ID pada formulir, klik Simpan.</li>
                    <li>Klik <strong class="text-ink">Test Kirim Pesan</strong> untuk verifikasi.</li>
                </ol>
            </x-card>
        </div>
    </div>

    @php($lastUpdate = $setting?->updater)

    @if ($lastUpdate)
        <p class="mt-4 max-w-5xl font-mono text-[10px] uppercase tracking-wider text-faint">Terakhir diperbarui oleh {{ $lastUpdate->name }} pada {{ $setting->updated_at->format('d M Y, H:i') }}.</p>
    @endif
@endsection

@push('scripts')
    <script>
        const variableList = @json($variables);

        function insertVars(textareaId) {
            const textarea = document.getElementById(textareaId);
            textarea.value += (textarea.value.endsWith('\n') || !textarea.value ? '' : '\n') + '{ticket_number}';
            textarea.focus();
        }

        document.getElementById('test-send-btn')?.addEventListener('click', async function () {
            const btn = this;
            const result = document.getElementById('test-result');
            const originalContent = btn.innerHTML;
            btn.disabled = true;
            btn.textContent = 'Mengirim…';
            result.classList.add('hidden');

            try {
                const res = await fetch("{{ route('admin.telegram-settings.test') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        Accept: 'application/json',
                    },
                });

                const data = await res.json();

                result.textContent = data.message;
                result.className = 'mt-4 rounded-[5px] border-l-4 px-3 py-2.5 text-sm font-medium ' + (data.ok ? 'border-ok-500 bg-ok-50 text-ok-700' : 'border-critical-500 bg-critical-50 text-critical-700');
                result.classList.remove('hidden');
            } catch {
                result.textContent = 'Terjadi kesalahan jaringan saat melakukan test.';
                result.className = 'mt-4 rounded-[5px] border-l-4 px-3 py-2.5 text-sm font-medium border-critical-500 bg-critical-50 text-critical-700';
                result.classList.remove('hidden');
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalContent;
            }
        });
    </script>
@endpush
