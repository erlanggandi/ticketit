@extends('layouts.admin')

@section('title', 'AI')

@section('page_title', 'Pengaturan AI')

@section('page_subtitle', 'Provider AI untuk diagnosis tiket')

@section('content')
    <div class="grid max-w-5xl grid-cols-1 gap-6 xl:grid-cols-3">
        <div class="space-y-6 xl:col-span-2">
            <x-card title="Provider AI">
                <form method="POST" action="{{ route('admin.ai-settings.update') }}" data-disable-on-submit class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="label" for="ai-provider">Provider AI</label>
                        <select id="ai-provider" name="provider" class="field appearance-none py-2 pr-8">
                            @foreach ($aiProviders as $value => $label)
                                <option value="{{ $value }}" @selected(($setting->provider ?? 'gemini') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="label" for="ai-api_key">API Key</label>
                        <input id="ai-api_key" name="api_key" type="password" class="field" autocomplete="new-password"
                               placeholder="{{ $setting?->api_key ? 'Biarkan kosong jika tidak ingin mengubah' : 'Masukkan API Key AI' }}" />
                        @if ($setting?->api_key)
                            <p class="mt-1.5 text-xs leading-relaxed text-faint">API Key tersimpan: {{ $setting->hasApiKey() ? 'tersimpan' : 'kosong' }}</p>
                        @endif
                    </div>

                    <div>
                        <label class="label" for="ai-base_url">Base URL</label>
                        <input id="ai-base_url" name="base_url" type="text" class="field" placeholder="https://api.openai.com/v1" />
                    </div>

                    <div>
                        <label class="label" for="ai-model">Model</label>
                        <input id="ai-model" name="model" type="text" list="ai-model-options" class="field" placeholder="mis. gemini-2.0-flash" />
                        <datalist id="ai-model-options">
                            @if ($setting?->model)
                                <option value="{{ $setting->model }}" selected></option>
                            @endif
                        </datalist>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="label" for="ai-temperature">Temperature</label>
                            <input id="ai-temperature" name="temperature" type="number" step="0.01" min="0" max="2" class="field" />
                        </div>
                        <div>
                            <label class="label" for="ai-max_tokens">Max Tokens</label>
                            <input id="ai-max_tokens" name="max_tokens" type="number" min="1" max="8192" class="field" />
                        </div>
                    </div>

                    <x-button type="submit">Simpan Pengaturan AI</x-button>
                </form>
            </x-card>
        </div>

        <div class="space-y-6">
            <x-card title="Test Koneksi">
                <p class="mb-4 text-sm leading-relaxed text-muted">
                    Test koneksi provider AI dan ambil daftar model yang tersedia untuk dipilih.
                </p>
                <button type="button" id="test-ai-btn" class="btn btn-secondary w-full">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5L19.5 4.5M19.5 9H9a3 3 0 0 0-3 3v6a3 3 0 0 0 3 3h10" />
                    </svg>
                    Test Koneksi AI
                </button>

                <div id="ai-test-result" class="mt-4 hidden rounded-[5px] border-l-4 px-3 py-2.5 text-sm font-medium"></div>

                @if (! $setting?->hasApiKey())
                    <p class="mt-3 font-mono text-[11px] uppercase tracking-wide text-signal-600">API Key wajib diisi sebelum test koneksi.</p>
                @endif
            </x-card>

            <x-card title="Cara Setup">
                <ol class="list-decimal space-y-2 pl-4 text-sm leading-relaxed text-muted marker:font-mono marker:text-faint">
                    <li>Pilih provider AI (Gemini, OpenAI, Anthropic, OpenRouter, atau custom).</li>
                    <li>Isi API Key dan model yang sesuai.</li>
                    <li>Klik <strong class="text-ink">Test Koneksi AI</strong>.</li>
                    <li>Pilih model dari daftar hasil test.</li>
                </ol>
            </x-card>
        </div>
    </div>

    @php($lastUpdate = $setting?->updater)

    @if ($lastUpdate)
        <p class="mt-6 max-w-5xl font-mono text-[10px] uppercase tracking-wider text-faint">
            Terakhir diperbarui oleh {{ $lastUpdate->name }} pada {{ optional($setting->updated_at)->format('d M Y, H:i') ?? '-' }}.
        </p>
    @endif

    @push('scripts')
        <script>
            const aiProviders = @json($aiProviders);
            const aiSettingsUrl = @json(route('admin.ai-settings.update'));
            const aiTestUrl = @json(route('admin.ai-settings.test'));

            const aiForm = document.querySelector('form[action="' + aiSettingsUrl + '"]');

            function aiSelectedProvider() {
                const provider = document.getElementById('ai-provider');
                return provider ? provider.value : 'gemini';
            }

            async function testAiConnection() {
                const btn = document.getElementById('test-ai-btn');
                const result = document.getElementById('ai-test-result');
                const originalContent = btn.innerHTML;

                btn.disabled = true;
                btn.textContent = 'Menguji…';
                result.classList.add('hidden');

                const body = {
                    provider: aiSelectedProvider(),
                    api_key: document.getElementById('ai-api_key').value,
                    base_url: document.getElementById('ai-base_url').value,
                    model: document.getElementById('ai-model').value,
                    temperature: document.getElementById('ai-temperature').value,
                    max_tokens: document.getElementById('ai-max_tokens').value,
                };

                try {
                    const res = await fetch(aiTestUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            Accept: 'application/json',
                        },
                        body: JSON.stringify(body),
                    });

                    const data = await res.json();

                    result.textContent = data.message ?? 'Test koneksi gagal.';
                    result.className = 'mt-4 rounded-[5px] border-l-4 px-3 py-2.5 text-sm font-medium ' + (data.ok ? 'border-ok-500 bg-ok-50 text-ok-700' : 'border-critical-500 bg-critical-50 text-critical-700');
                    result.classList.toggle('hidden', Boolean(data.ok));
                    result.classList.toggle('border-ok-500', Boolean(data.ok));
                    result.classList.toggle('bg-ok-50', Boolean(data.ok));
                    result.classList.toggle('text-ok-700', Boolean(data.ok));

                    const datalist = document.getElementById('ai-model-options');
                    datalist.innerHTML = '';

                    (data.models ?? []).forEach((model) => {
                        const option = document.createElement('option');
                        option.value = model;
                        option.textContent = model;
                        datalist.appendChild(option);
                    });
                } catch {
                    result.textContent = 'Terjadi kesalahan jaringan saat melakukan test koneksi.';
                    result.className = 'mt-4 rounded-[5px] border-l-4 px-3 py-2.5 text-sm font-medium border-critical-500 bg-critical-50 text-critical-700';
                    result.classList.remove('hidden');
                } finally {
                    btn.disabled = false;
                    btn.innerHTML = originalContent;
                }
            }

            document.getElementById('test-ai-btn')?.addEventListener('click', testAiConnection);
        </script>
    @endpush
@endsection
