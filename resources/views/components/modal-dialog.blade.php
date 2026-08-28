@props(['id', 'title'])

<dialog id="{{ $id }}" class="max-w-md mx-auto rounded-md border border-ink bg-sheet p-0 shadow-[var(--shadow-pop)] backdrop:bg-ink/55">
    <div class="flex items-center justify-between gap-4 border-b border-ink px-6 py-3.5">
        <h3 class="font-mono text-[11px] font-semibold uppercase tracking-[0.16em] text-ink">{{ $title }}</h3>
        <button type="button" onclick="closeDialog('{{ $id }}')" class="-m-1 rounded-[4px] p-1 text-muted transition hover:text-critical-600" aria-label="Tutup">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <div class="p-6">
        {{ $slot }}
    </div>
</dialog>
