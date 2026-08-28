@extends('layouts.admin')

@section('title', 'Karyawan')
@section('page_title', 'Master Data — Karyawan')
@section('page_subtitle', 'Data untuk autocomplete pada form tiket publik')

@section('content')
    <x-card :padded="false">
        <div class="flex items-center justify-between border-b border-dashed border-rule bg-paper/50 px-6 py-3">
            <p class="font-mono text-[10px] uppercase tracking-[0.14em] text-muted">Total <strong class="text-ink">{{ $employees->total() }}</strong> karyawan</p>
            <div class="flex items-center gap-3">
                <x-button size="sm" onclick="openCreateDialog()">+ Tambah Karyawan</x-button>
                <span class="h-5 w-px bg-rule"></span>
                <a href="{{ route('admin.employees.export') }}"
                    class="font-mono text-[10px] font-semibold uppercase tracking-wide text-stamp-700 underline decoration-stamp-300 underline-offset-4 hover:text-stamp-900">Ekspor CSV &darr;</a>
                <button type="button" onclick="openDialog('employee-import-dialog')"
                    class="font-mono text-[10px] font-semibold uppercase tracking-wide text-stamp-700 underline decoration-stamp-300 underline-offset-4 hover:text-stamp-900">Impor CSV &uarr;</button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[640px] text-left text-sm">
                <thead>
                    <tr class="border-b border-rule bg-paper/60 font-mono text-[10px] uppercase tracking-[0.14em] text-muted">
                        <th class="px-6 py-2.5 font-semibold">Nama</th>
                        <th class="px-6 py-2.5 font-semibold">Departemen</th>
                        <th class="px-6 py-2.5 font-semibold">Kontak</th>
                        <th class="px-6 py-2.5 font-semibold">Status</th>
                        <th class="px-6 py-2.5 text-right font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-rule/70">
                    @forelse ($employees as $employee)
                        <tr class="transition hover:bg-paper/70">
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-[3px] border border-stamp-600/40 bg-stamp-50 font-mono text-[11px] font-semibold text-stamp-800">
                                        {{ collect(explode(' ', $employee->employee_name))->map(fn ($w) => mb_substr($w, 0, 1))->slice(0, 2)->implode('') }}
                                    </span>
                                    <span class="font-medium text-ink">{{ $employee->employee_name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-3.5 text-muted">{{ $employee->department ?? '—' }}</td>
                            <td class="px-6 py-3.5 text-muted">{{ $employee->contact ?? '—' }}</td>
                            <td class="px-6 py-3.5">
                                <span class="chip {{ $employee->is_active ? 'border-ok-500/60 bg-ok-50 text-ok-700' : 'border-ink/25 bg-ink/[0.06] text-muted' }}">
                                    {{ $employee->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-3.5 text-right">
                                <button type="button" class="font-mono text-[10px] font-semibold uppercase tracking-wide text-stamp-700 underline decoration-stamp-300 underline-offset-4 hover:text-stamp-800"
                                        data-payload="{{ json_encode(['id' => $employee->id, 'employee_name' => $employee->employee_name, 'department' => $employee->department, 'contact' => $employee->contact]) }}"
                                        onclick="openEditDialog(this)">Edit</button>
                                <span class="mx-2 text-rule">|</span>
                                <form method="POST" action="{{ route('admin.employees.toggle', $employee) }}" class="inline" onsubmit="return confirm('Ubah status karyawan ini?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="font-mono text-[10px] font-semibold uppercase tracking-wide text-signal-600 hover:text-signal-700">{{ $employee->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                                </form>
                                @if ($employee->tickets()->count() === 0)
                                    <span class="mx-2 text-rule">|</span>
                                    <form method="POST" action="{{ route('admin.employees.destroy', $employee) }}" class="inline" onsubmit="return confirm('Hapus data {{ $employee->employee_name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-mono text-[10px] font-semibold uppercase tracking-wide text-critical-600 hover:text-critical-700">Hapus</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-10"><x-empty-state description="Belum ada data karyawan." /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-dashed border-rule px-6 py-3">
            {{ $employees->links() }}
        </div>
    </x-card>

    <x-modal-dialog id="employee-dialog" title="Data Karyawan">
        <form method="POST" id="employee-form" action="{{ route('admin.employees.store') }}" data-disable-on-submit class="space-y-4">
            @csrf

            <x-input name="employee_name" label="Nama Lengkap" :required="true" placeholder="mis. Budi Santoso" maxlength="150" />

            <div class="grid gap-4 sm:grid-cols-2">
                <x-input name="department" label="Departemen" placeholder="mis. Finance" maxlength="100" />
                <x-input name="contact" label="Kontak (email/telp)" placeholder="opsional" maxlength="150" />
            </div>

            <label class="flex items-center gap-2.5 text-sm text-muted">
                <input type="checkbox" name="is_active" value="1" checked class="h-4 w-4 rounded-[3px] border-ink/40 accent-stamp-600" />
                Aktif (ikut autocomplete form publik)
            </label>

            <div class="flex justify-end gap-2 pt-1">
                <x-button variant="secondary" size="sm" type="button" onclick="closeDialog('employee-dialog')">Batal</x-button>
                <x-button size="sm" type="submit">Simpan</x-button>
            </div>
        </form>
    </x-modal-dialog>

    <x-modal-dialog id="employee-import-dialog" title="Impor Karyawan (CSV)">
        <form method="POST" action="{{ route('admin.employees.import') }}" enctype="multipart/form-data" data-disable-on-submit class="space-y-4">
            @csrf

            <p class="text-xs leading-relaxed text-muted">
                Kolom: <strong class="text-ink">employee_name, department, contact, is_active</strong>.
                Baris dengan nama yang sudah ada akan <strong class="text-ink">diperbarui</strong> dari isi file;
                kolom kosong pada departemen/kontak akan dikosongkan, sedangkan is_active kosong mempertahankan status lama
                (baru = Aktif). Impor tidak pernah menghapus data.
            </p>

            <input type="file" name="file" accept=".csv,text/csv" required
                class="field w-full py-2 file:mr-3 file:rounded-[4px] file:border-0 file:bg-stamp-50 file:px-3 file:py-1.5 file:font-mono file:text-[10px] file:font-semibold file:uppercase file:tracking-wide file:text-stamp-800" />

            @error('file')
                <p class="font-mono text-[11px] uppercase tracking-wide text-critical-600">{{ $message }}</p>
            @enderror

            <div class="flex justify-end gap-2 pt-1">
                <x-button variant="secondary" size="sm" type="button" onclick="closeDialog('employee-import-dialog')">Batal</x-button>
                <x-button size="sm" type="submit">Impor Sekarang</x-button>
            </div>
        </form>
    </x-modal-dialog>

    <script>
        const storeUrl = @json(route('admin.employees.store'));
        const updateUrlTemplate = @json(url('/admin/employees/__ID__'));

        function removeMethodField(form) {
            form.querySelector('input[name="_method"]')?.remove();
        }

        function openCreateDialog() {
            const form = document.getElementById('employee-form');
            form.reset();
            form.querySelector('[name="is_active"]').checked = true;
            removeMethodField(form);
            form.action = storeUrl;
            openDialog('employee-dialog');
        }

        function openEditDialog(buttonEl) {
            const data = JSON.parse(buttonEl.dataset.payload);
            const form = document.getElementById('employee-form');
            form.reset();

            let methodField = form.querySelector('input[name="_method"]');
            if (!methodField) {
                methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'PATCH';
                form.prepend(methodField);
            }

            form.action = updateUrlTemplate.replace('__ID__', data.id);
            form.querySelector('[name="employee_name"]').value = data.employee_name;
            form.querySelector('[name="department"]').value = data.department ?? '';
            form.querySelector('[name="contact"]').value = data.contact ?? '';
            form.querySelector('[name="is_active"]').checked = false;

            openDialog('employee-dialog');
        }
    </script>
@endsection
