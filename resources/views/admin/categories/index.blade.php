@extends('layouts.admin')

@section('title', 'Kategori')
@section('page_title', 'Master Data — Kategori')
@section('page_subtitle', 'Kelola daftar kategori keluhan')

@section('content')
    <x-card :padded="false">
        <div class="flex items-center justify-between border-b border-dashed border-rule bg-paper/50 px-6 py-3">
            <p class="font-mono text-[10px] uppercase tracking-[0.14em] text-muted">Total <strong class="text-ink">{{ $categories->count() }}</strong> kategori</p>
            <x-button size="sm" onclick="openCreateDialog()">+ Tambah Kategori</x-button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[560px] text-left text-sm">
                <thead>
                    <tr class="border-b border-rule bg-paper/60 font-mono text-[10px] uppercase tracking-[0.14em] text-muted">
                        <th class="px-6 py-2.5 font-semibold">Nama Kategori</th>
                        <th class="px-6 py-2.5 font-semibold">Jumlah Tiket</th>
                        <th class="px-6 py-2.5 font-semibold">Status</th>
                        <th class="px-6 py-2.5 text-right font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-rule/70">
                    @forelse ($categories as $category)
                        <tr class="transition hover:bg-paper/70">
                            <td class="px-6 py-3.5 font-medium text-ink">{{ $category->category_name }}</td>
                            <td class="px-6 py-3.5 font-mono tabular-nums text-muted">{{ $category->tickets_count }}</td>
                            <td class="px-6 py-3.5">
                                <span class="chip {{ $category->is_active ? 'border-ok-500/60 bg-ok-50 text-ok-700' : 'border-ink/25 bg-ink/[0.06] text-muted' }}">
                                    {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-3.5 text-right">
                                <button type="button" class="font-mono text-[10px] font-semibold uppercase tracking-wide text-stamp-700 underline decoration-stamp-300 underline-offset-4 hover:text-stamp-800"
                                        data-payload="{{ json_encode(['id' => $category->id, 'category_name' => $category->category_name]) }}"
                                        onclick="openEditDialog(this)">Edit</button>
                                <span class="mx-2 text-rule">|</span>
                                <form method="POST" action="{{ route('admin.categories.toggle', $category) }}" class="inline" onsubmit="return confirm('Ubah status kategori ini?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="font-mono text-[10px] font-semibold uppercase tracking-wide text-signal-600 hover:text-signal-700">{{ $category->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                                </form>
                                <span class="mx-2 text-rule">|</span>
                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="inline" onsubmit="return confirm('Hapus kategori {{ $category->category_name }}? Ditolak bila masih dipakai tiket.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-mono text-[10px] font-semibold uppercase tracking-wide text-critical-600 hover:text-critical-700">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-6 py-10"><x-empty-state description="Belum ada kategori." /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    <x-modal-dialog id="category-dialog" title="Data Kategori">
        <form method="POST" id="category-form" action="{{ route('admin.categories.store') }}" data-disable-on-submit class="space-y-4">
            @csrf
            <input type="hidden" name="_method" value="">

            <x-input name="category_name" label="Nama Kategori" :required="true" placeholder="mis. Hardware" maxlength="100" />

            <label class="flex items-center gap-2.5 text-sm text-muted">
                <input type="checkbox" name="is_active" value="1" checked class="h-4 w-4 rounded-[3px] border-ink/40 accent-stamp-600" />
                Aktif (tampil pada form tiket publik)
            </label>

            <div class="flex justify-end gap-2 pt-1">
                <x-button variant="secondary" size="sm" type="button" onclick="closeDialog('category-dialog')">Batal</x-button>
                <x-button size="sm" type="submit">Simpan</x-button>
            </div>
        </form>
    </x-modal-dialog>

    <script>
        const storeUrl = @json(route('admin.categories.store'));
        const updateUrlTemplate = @json(url('/admin/categories/__ID__'));

        function removeMethodField(form) {
            form.querySelector('input[name="_method"]')?.remove();
        }

        function openCreateDialog() {
            const form = document.getElementById('category-form');
            form.reset();
            form.querySelector('[name="is_active"]').checked = true;
            removeMethodField(form);
            form.action = storeUrl;
            openDialog('category-dialog');
        }

        function openEditDialog(buttonEl) {
            const data = JSON.parse(buttonEl.dataset.payload);
            const form = document.getElementById('category-form');
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
            form.querySelector('[name="category_name"]').value = data.category_name;
            form.querySelector('[name="is_active"]').checked = false;

            openDialog('category-dialog');
        }
    </script>
@endsection
