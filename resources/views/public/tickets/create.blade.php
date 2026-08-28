@extends('layouts.public')

@section('title', 'Buat Tiket')

@section('content')
    <div class="space-y-6">
        <x-card :padded="false">
            <div class="kop">
                <span>No. GSI4-IT-001E</span>
                <span class="hidden sm:inline">{{ now()->translatedFormat('d M Y') }}</span>
                <span>Lembar 1/1</span>
            </div>

            <div class="p-5 sm:p-7">
                @php
                    $ss = $siteSetting ?? (object) [
                        'form_title' => 'Lapor Masalah IT',
                        'form_description' => 'Isi formulir ini untuk mencatat kendala yang Anda alami. Tim IT menangani sesuai tingkat urgensi.',
                    ];
                @endphp
                <h1 class="display text-2xl sm:text-3xl">{{ $ss->form_title }}</h1>
                @if ($ss->form_description)
                    <p class="mt-2 max-w-md text-sm leading-relaxed text-muted">{{ $ss->form_description }}</p>
                @endif
                @if ($ss->form_description)
                    <p class="mt-2 max-w-md text-sm leading-relaxed text-muted">{{ $ss->form_description }}</p>
                @endif

                <form method="POST" action="{{ route('tickets.store') }}" data-disable-on-submit class="mt-7 space-y-6" autocomplete="off">
                    @csrf

                    <div class="relative" id="autocomplete-wrap">
                        <label for="requester_name" class="label">
                            <span class="mr-2 text-faint">01 .</span>Nama Pelapor <span class="text-critical-500" aria-hidden="true">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" name="requester_name" id="requester_name"
                                value="{{ old('requester_name') }}"
                                required minlength="2" maxlength="150"
                                placeholder="Ketik nama Anda…"
                                class="field {{ $errors->has('requester_name') ? 'field-error' : '' }}" />
                        </div>
                        <input type="hidden" name="employee_id" id="employee_id" value="{{ old('employee_id') }}">

                        <div id="autocomplete-list" class="absolute z-10 hidden w-full overflow-hidden rounded-[5px] border border-ink bg-sheet shadow-[var(--shadow-pop)]"></div>

                        <p class="mt-1.5 text-xs leading-relaxed text-faint">Jika nama Anda terdaftar, saran akan muncul otomatis. Tidak terdaftar? Lanjutkan saja secara manual.</p>

                        @error('requester_name')
                            <p class="mt-1.5 font-mono text-[11px] uppercase tracking-wide text-critical-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        <x-select name="category_id" label="02 · Kategori Masalah" :required="true"
                            placeholder="Pilih kategori…" :selected="old('category_id')"
                            :options="$categories->pluck('category_name', 'id')" />

                        <x-select name="priority" label="03 · Tingkat Urgensi" :required="true"
                            placeholder="Pilih urgensi…" :selected="old('priority')"
                            hint="Menentukan target waktu penanganan (SLA)."
                            :options="['Low' => 'Low — santai', 'Medium' => 'Medium — normal', 'High' => 'High — mengganggu kerja', 'Critical' => 'Critical — operasional berhenti']" />
                    </div>

                    <div>
                        <x-textarea name="description" label="04 · Deskripsi Masalah" :required="true" rows="5"
                                    placeholder="Jelaskan masalah yang dialami: apa yang terjadi, sejak kapan, dan pesan error jika ada…">{{ old('description') }}</x-textarea>
                    </div>

                    <div class="flex flex-col gap-3 border-t border-dashed border-rule pt-6 sm:flex-row sm:items-center sm:justify-between">
                        <p class="font-mono text-[10px] uppercase tracking-[0.16em] text-faint">Semua field bertanda * wajib diisi</p>
                        <x-button type="submit" size="lg">
                            Kirim Laporan
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </x-button>
                    </div>
                </form>
            </div>
        </x-card>

        <p class="text-center font-mono text-[11px] uppercase tracking-[0.14em] text-faint">
            Sudah punya tiket?
            <a href="{{ route('tickets.check') }}" class="font-semibold text-stamp-700 underline decoration-stamp-300 underline-offset-4 hover:text-stamp-800">Cek status di sini</a>
        </p>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            const input = document.getElementById('requester_name');
            const hiddenId = document.getElementById('employee_id');
            const list = document.getElementById('autocomplete-list');
            const wrap = document.getElementById('autocomplete-wrap');
            let controller = null;
            let debounceTimer = null;

            function hideList() {
                list.classList.add('hidden');
                list.innerHTML = '';
            }

            input.addEventListener('blur', () => setTimeout(hideList, 180));

            input.addEventListener('input', () => {
                hiddenId.value = '';
                clearTimeout(debounceTimer);
                const q = input.value.trim();

                if (q.length < 2) {
                    hideList();
                    return;
                }

                debounceTimer = setTimeout(async () => {
                    if (controller) controller.abort();
                    controller = new AbortController();

                    try {
                        const res = await fetch("{{ route('api.employees.search') }}?q=" + encodeURIComponent(q), {
                            headers: { Accept: 'application/json' },
                            signal: controller.signal,
                        });

                        if (!res.ok) return;
                        const json = await res.json();

                        if (!json.data.length) {
                            hideList();
                            return;
                        }

                        list.innerHTML = '';
                        json.data.forEach((emp) => {
                            const btn = document.createElement('button');
                            btn.type = 'button';
                            btn.className = 'flex w-full items-center gap-3 px-4 py-2.5 text-left transition hover:bg-paper';
                            btn.innerHTML =
                                '<span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-[3px] border border-stamp-600/50 bg-stamp-50 font-mono text-[11px] font-semibold text-stamp-800">' +
                                    emp.name.split(' ').map(w => w[0]).slice(0, 2).join('').toUpperCase() +
                                '</span>' +
                                '<span><span class="block text-sm font-semibold text-ink">' + emp.name + '</span>' +
                                '<span class="block text-xs text-muted">' + (emp.department ?? '-') + '</span></span>';

                            btn.addEventListener('mousedown', (e) => {
                                e.preventDefault();
                                input.value = emp.name;
                                hiddenId.value = emp.id;
                                hideList();
                            });

                            list.appendChild(btn);
                        });

                        list.classList.remove('hidden');
                    } catch (e) {}
                }, 250);
            });

            document.addEventListener('click', (e) => {
                if (!wrap.contains(e.target)) hideList();
            });
        })();
    </script>
@endpush
