@extends('layouts.admin')

@section('title', 'Teknisi')
@section('page_title', 'Master Data — Teknisi')
@section('page_subtitle', 'Tim teknis penanggung jawab penyelesaian tiket')

@section('content')
    <x-card :padded="false">
        <div class="flex items-center justify-between border-b border-dashed border-rule bg-paper/50 px-6 py-3">
            <p class="font-mono text-[10px] uppercase tracking-[0.14em] text-muted">Total <strong class="text-ink">{{ $technicians->count() }}</strong> teknisi</p>
            <x-button size="sm" onclick="openCreateDialog()">+ Tambah Teknisi</x-button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[640px] text-left text-sm">
                <thead>
                    <tr class="border-b border-rule bg-paper/60 font-mono text-[10px] uppercase tracking-[0.14em] text-muted">
                        <th class="px-6 py-2.5 font-semibold">Nama</th>
                        <th class="px-6 py-2.5 font-semibold">Keahlian</th>
                        <th class="px-6 py-2.5 font-semibold">Kontak</th>
                        <th class="px-6 py-2.5 font-semibold">Status</th>
                        <th class="px-6 py-2.5 text-right font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-rule/70">
                    @forelse ($technicians as $technician)
                        <tr class="transition hover:bg-paper/70">
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-[3px] border border-ok-500/40 bg-ok-50 font-mono text-[11px] font-semibold text-ok-700">
                                        {{ $technician->initials() }}
                                    </span>
                                    <span class="font-medium text-ink">{{ $technician->technician_name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-3.5 text-muted">{{ $technician->expertise ?? '—' }}</td>
                            <td class="px-6 py-3.5 text-muted">{{ $technician->contact ?? '—' }}</td>
                            <td class="px-6 py-3.5">
                                <span class="chip {{ $technician->is_active ? 'border-ok-500/60 bg-ok-50 text-ok-700' : 'border-ink/25 bg-ink/[0.06] text-muted' }}">
                                    {{ $technician->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-3.5 text-right">
                                <button type="button" class="font-mono text-[10px] font-semibold uppercase tracking-wide text-stamp-700 underline decoration-stamp-300 underline-offset-4 hover:text-stamp-800"
                                        data-payload="{{ json_encode(['id' => $technician->id, 'technician_name' => $technician->technician_name, 'expertise' => $technician->expertise, 'contact' => $technician->contact]) }}"
                                        onclick="openEditDialog(this)">Edit</button>
                                <span class="mx-2 text-rule">|</span>
                                <form method="POST" action="{{ route('admin.technicians.toggle', $technician) }}" class="inline" onsubmit="return confirm('Ubah status teknisi ini?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="font-mono text-[10px] font-semibold uppercase tracking-wide text-signal-600 hover:text-signal-700">{{ $technician->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                                </form>
                                <span class="mx-2 text-rule">|</span>
                                <form method="POST" action="{{ route('admin.technicians.destroy', $technician) }}" class="inline" onsubmit="return confirm('Hapus data {{ $technician->technician_name }}? Tiket terkait akan kehilangan penanggung jawab.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-mono text-[10px] font-semibold uppercase tracking-wide text-critical-600 hover:text-critical-700">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-10"><x-empty-state description="Belum ada data teknisi." /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    <x-modal-dialog id="technician-dialog" title="Data Teknisi">
        <form method="POST" id="technician-form" action="{{ route('admin.technicians.store') }}" data-disable-on-submit class="space-y-4">
            @csrf

            <x-input name="technician_name" label="Nama Teknisi" :required="true" placeholder="mis. Ahmad Fauzi" maxlength="150" />

            <div class="grid gap-4 sm:grid-cols-2">
                <x-input name="expertise" label="Keahlian" placeholder="mis. Jaringan, Hardware" maxlength="150" />
                <x-input name="contact" label="Kontak (email/telp)" placeholder="opsional" maxlength="150" />
            </div>

            <label class="flex items-center gap-2.5 text-sm text-muted">
                <input type="checkbox" name="is_active" value="1" checked class="h-4 w-4 rounded-[3px] border-ink/40 accent-stamp-600" />
                Aktif (bisa dipilih saat penugasan tiket)
            </label>

            <div class="flex justify-end gap-2 pt-1">
                <x-button variant="secondary" size="sm" type="button" onclick="closeDialog('technician-dialog')">Batal</x-button>
                <x-button size="sm" type="submit">Simpan</x-button>
            </div>
        </form>
    </x-modal-dialog>

    <script>
        const storeUrl = @json(route('admin.technicians.store'));
        const updateUrlTemplate = @json(url('/admin/technicians/__ID__'));

        function removeMethodField(form) {
            form.querySelector('input[name="_method"]')?.remove();
        }

        function openCreateDialog() {
            const form = document.getElementById('technician-form');
            form.reset();
            form.querySelector('[name="is_active"]').checked = true;
            removeMethodField(form);
            form.action = storeUrl;
            openDialog('technician-dialog');
        }

        function openEditDialog(buttonEl) {
            const data = JSON.parse(buttonEl.dataset.payload);
            const form = document.getElementById('technician-form');
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
            form.querySelector('[name="technician_name"]').value = data.technician_name;
            form.querySelector('[name="expertise"]').value = data.expertise ?? '';
            form.querySelector('[name="contact"]').value = data.contact ?? '';
            form.querySelector('[name="is_active"]').checked = false;

            openDialog('technician-dialog');
        }
    </script>
@endsection
