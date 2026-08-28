@extends('layouts.admin')

@section('title', 'Pengaturan Website')
@section('page_title', 'Pengaturan Website')
@section('page_subtitle', 'Identitas & tampilan halaman publik')

@section('content')
    <form method="POST" action="{{ route('admin.site-settings.update') }}" enctype="multipart/form-data" data-disable-on-submit class="space-y-6 max-w-4xl">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Card 1: Identitas Website -->
            <x-card title="Identitas Website">
                <div class="space-y-5">
                    <div>
                        <label for="app_name" class="label">Nama Aplikasi <span class="text-critical-500" aria-hidden="true">*</span></label>
                        <input id="app_name" type="text" name="app_name" value="{{ old('app_name', $setting->app_name) }}" class="field @error('app_name') field-error @endif" required maxlength="100" />
                        @error('app_name')<p class="mt-1.5 font-mono text-[11px] uppercase tracking-wide text-critical-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="app_short_name" class="label">Nama Singkat (Logo) <span class="text-critical-500" aria-hidden="true">*</span></label>
                        <input id="app_short_name" type="text" name="app_short_name" value="{{ old('app_short_name', $setting->app_short_name) }}" class="field @error('app_short_name') field-error @endif" required maxlength="20" />
                        <p class="mt-1.5 text-xs leading-relaxed text-faint">Ditampilkan sebagai inisial logo di header (mis. "IT").</p>
                        @error('app_short_name')<p class="mt-1.5 font-mono text-[11px] uppercase tracking-wide text-critical-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="tagline" class="label">Tagline <span class="text-critical-500" aria-hidden="true">*</span></label>
                        <input id="tagline" type="text" name="tagline" value="{{ old('tagline', $setting->tagline) }}" class="field @error('tagline') field-error @endif" required maxlength="150" />
                        <p class="mt-1.5 text-xs leading-relaxed text-faint">Sub-judul di bawah nama aplikasi pada header.</p>
                        @error('tagline')<p class="mt-1.5 font-mono text-[11px] uppercase tracking-wide text-critical-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="footer_text" class="label">Teks Footer <span class="text-critical-500" aria-hidden="true">*</span></label>
                        <input id="footer_text" type="text" name="footer_text" value="{{ old('footer_text', $setting->footer_text) }}" class="field @error('footer_text') field-error @endif" required maxlength="200" />
                        <p class="mt-1.5 text-xs leading-relaxed text-faint">Tahun otomatis ditambahkan di akhir.</p>
                        @error('footer_text')<p class="mt-1.5 font-mono text-[11px] uppercase tracking-wide text-critical-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </x-card>

            <!-- Card 2: Tampilan Form -->
            <x-card title="Tampilan Form Tiket">
                <div class="space-y-5">
                    <div>
                        <label for="form_title" class="label">Judul Form <span class="text-critical-500" aria-hidden="true">*</span></label>
                        <input id="form_title" type="text" name="form_title" value="{{ old('form_title', $setting->form_title) }}" class="field @error('form_title') field-error @endif" required maxlength="200" />
                        @error('form_title')<p class="mt-1.5 font-mono text-[11px] uppercase tracking-wide text-critical-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="form_description" class="label">Deskripsi Form</label>
                        <textarea id="form_description" name="form_description" rows="4" class="field @error('form_description') field-error @endif" maxlength="1000">{{ old('form_description', $setting->form_description) }}</textarea>
                        <p class="mt-1.5 text-xs leading-relaxed text-faint">Penjelasan singkat di bawah judul form.</p>
                        @error('form_description')<p class="mt-1.5 font-mono text-[11px] uppercase tracking-wide text-critical-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Card 3: Logo & Favicon -->
        <x-card title="Logo & Favicon">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label for="logo" class="label">Logo (PNG / SVG, maks 512 KB)</label>
                    <input id="logo" type="file" name="logo" accept="image/png,image/x-icon,image/svg+xml" class="field @error('logo') field-error @endif" />
                    @error('logo')<p class="mt-1.5 font-mono text-[11px] uppercase tracking-wide text-critical-600">{{ $message }}</p>@enderror

                    @if ($setting->logo_path)
                        <div class="mt-3 flex items-center gap-3 rounded-[5px] border border-dashed border-rule bg-paper/60 p-3">
                            <img src="{{ asset('storage/'.$setting->logo_path) }}" alt="Logo saat ini" class="h-12 w-12 rounded-[3px] border border-rule object-contain bg-sheet" />
                            <div class="min-w-0 flex-1">
                                <p class="font-mono text-[10px] uppercase tracking-wide text-muted">Logo saat ini</p>
                                <p class="truncate font-mono text-[11px] text-ink">{{ $setting->logo_path }}</p>
                            </div>
                            <label class="flex items-center gap-1.5 text-xs text-critical-600">
                                <input type="checkbox" name="remove_logo" value="1" /> Hapus
                            </label>
                        </div>
                    @endif
                </div>

                <div>
                    <label for="favicon" class="label">Favicon (PNG / ICO / SVG, maks 256 KB)</label>
                    <input id="favicon" type="file" name="favicon" accept="image/png,image/x-icon,image/svg+xml" class="field @error('favicon') field-error @endif" />
                    @error('favicon')<p class="mt-1.5 font-mono text-[11px] uppercase tracking-wide text-critical-600">{{ $message }}</p>@enderror

                    @if ($setting->favicon_path)
                        <div class="mt-3 flex items-center gap-3 rounded-[5px] border border-dashed border-rule bg-paper/60 p-3">
                            <img src="{{ asset('storage/'.$setting->favicon_path) }}" alt="Favicon saat ini" class="h-8 w-8 rounded-[3px] border border-rule object-contain bg-sheet" />
                            <div class="min-w-0 flex-1">
                                <p class="font-mono text-[10px] uppercase tracking-wide text-muted">Favicon saat ini</p>
                                <p class="truncate font-mono text-[11px] text-ink">{{ $setting->favicon_path }}</p>
                            </div>
                            <label class="flex items-center gap-1.5 text-xs text-critical-600">
                                <input type="checkbox" name="remove_favicon" value="1" /> Hapus
                            </label>
                        </div>
                    @endif
                </div>
            </div>

            <p class="mt-4 border-t border-dashed border-rule pt-3 font-mono text-[10px] uppercase tracking-wide text-faint">
                File disimpan di storage publik. Jalankan <code class="bg-sheet px-1 py-0.5">php artisan storage:link</code> jika belum.
            </p>
        </x-card>

        <div class="flex justify-end">
            <x-button type="submit">Simpan Pengaturan</x-button>
        </div>
    </form>
@endsection
