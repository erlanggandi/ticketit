@extends('layouts.admin')

@section('title', 'Manajemen Admin')
@section('page_title', 'Manajemen Admin')
@section('page_subtitle', 'Akun administrator & pembaruan password')

@section('content')
    <div class="grid max-w-5xl grid-cols-1 gap-6 lg:grid-cols-[320px_1fr]">
        <!-- Kolom Kiri: Tabel Admin -->
        <x-card title="Admin Terdaftar">
            <div class="-mx-5 sm:-mx-6">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-ink/20">
                            <th class="px-5 py-2.5 text-left font-mono text-[10px] font-semibold uppercase tracking-[0.12em] text-muted sm:px-6">Nama</th>
                            <th class="px-5 py-2.5 text-left font-mono text-[10px] font-semibold uppercase tracking-[0.12em] text-muted">Email</th>
                            <th class="px-5 py-2.5 text-left font-mono text-[10px] font-semibold uppercase tracking-[0.12em] text-muted">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-t border-rule">
                            <td class="px-5 py-3 font-semibold text-ink sm:px-6">{{ $admin->name }}</td>
                            <td class="px-5 py-3 font-mono text-xs break-all text-muted">{{ $admin->email }}</td>
                            <td class="px-5 py-3">
                                <x-badge :value="$admin->is_active ? 'Active' : 'Inactive'" size="sm" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <p class="mt-4 border-t border-dashed border-rule pt-3 font-mono text-[10px] uppercase tracking-[0.12em] text-faint">
                Sistem saat ini menggunakan 1 akun administrator.
            </p>
        </x-card>

        <!-- Kolom Kanan: Akun Saya + Form Ganti Password -->
        <div class="space-y-6">
            <x-card title="Akun Saya">
                <dl class="space-y-3">
                    <div class="flex items-center justify-between gap-4 border-b border-dashed border-rule pb-3">
                        <dt class="font-mono text-[11px] font-medium uppercase tracking-[0.12em] text-muted">Nama</dt>
                        <dd class="text-sm font-semibold text-ink">{{ $admin->name }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-4 border-b border-dashed border-rule pb-3">
                        <dt class="font-mono text-[11px] font-medium uppercase tracking-[0.12em] text-muted">Email</dt>
                        <dd class="text-sm font-mono text-ink break-all">{{ $admin->email }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <dt class="font-mono text-[11px] font-medium uppercase tracking-[0.12em] text-muted">Status Akun</dt>
                        <dd><x-badge :value="$admin->is_active ? 'Active' : 'Inactive'" size="sm" /></dd>
                    </div>
                </dl>
            </x-card>

            <x-card title="Ubah Password">
                <form method="POST" action="{{ route('admin.admin-manage.updatePassword') }}" data-disable-on-submit class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="label">Password Lama <span class="text-critical-500" aria-hidden="true">*</span></label>
                        <input id="current_password" type="password" name="current_password" autocomplete="current-password" class="field @error('current_password') field-error @enderror" required />
                        @error('current_password')
                            <p class="mt-1.5 font-mono text-[11px] uppercase tracking-wide text-critical-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="label">Password Baru <span class="text-critical-500" aria-hidden="true">*</span></label>
                        <input id="password" type="password" name="password" autocomplete="new-password" class="field @error('password') field-error @enderror" required />
                        @error('password')
                            <p class="mt-1.5 font-mono text-[11px] uppercase tracking-wide text-critical-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 font-mono text-[10px] uppercase tracking-wide text-faint">
                            @php
                                $rules = collect([
                                    'Minimal ' . ($setting?->password_min_length ?? 8) . ' karakter',
                                    $setting?->password_require_uppercase ? 'Huruf besar' : null,
                                    $setting?->password_require_lowercase ? 'Huruf kecil' : null,
                                    $setting?->password_require_number ? 'Angka' : null,
                                    $setting?->password_require_symbol ? 'Simbol' : null,
                                ])->filter()->implode(' · ');
                            @endphp
                            Syarat: {{ $rules ?: '—' }}
                        </p>
                    </div>

                    <div>
                        <label for="password_confirmation" class="label">Konfirmasi Password Baru <span class="text-critical-500" aria-hidden="true">*</span></label>
                        <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" class="field" required />
                    </div>

                    <div class="flex justify-end gap-2 border-t border-dashed border-rule pt-4">
                        <x-button type="submit">Perbarui Password</x-button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
@endsection
