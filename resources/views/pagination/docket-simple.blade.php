@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Paginasi" class="flex flex-wrap items-center justify-between gap-3">
        <p class="font-mono text-[10px] uppercase tracking-[0.14em] text-faint">
            Menampilkan {{ $paginator->firstItem() }}&ndash;{{ $paginator->lastItem() }} data
        </p>

        <div class="flex items-center gap-1">
            @if ($paginator->onFirstPage())
                <span class="pointer-events-none select-none rounded-[4px] border border-transparent px-2.5 py-1 font-mono text-[10px] font-semibold uppercase tracking-wide text-faint/50">&larr; Prev</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Halaman sebelumnya"
                    class="rounded-[4px] border border-transparent px-2.5 py-1 font-mono text-[10px] font-semibold uppercase tracking-wide text-stamp-700 transition hover:bg-stamp-50 hover:text-stamp-900">&larr; Prev</a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Halaman berikutnya"
                    class="rounded-[4px] border border-transparent px-2.5 py-1 font-mono text-[10px] font-semibold uppercase tracking-wide text-stamp-700 transition hover:bg-stamp-50 hover:text-stamp-900">Next &rarr;</a>
            @else
                <span class="pointer-events-none select-none rounded-[4px] border border-transparent px-2.5 py-1 font-mono text-[10px] font-semibold uppercase tracking-wide text-faint/50">Next &rarr;</span>
            @endif
        </div>
    </nav>
@endif
