@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Paginasi" class="flex flex-wrap items-center justify-between gap-3">
        <p class="font-mono text-[10px] uppercase tracking-[0.14em] text-faint">
            Menampilkan {{ $paginator->firstItem() }}&ndash;{{ $paginator->lastItem() }} dari {{ $paginator->total() }} data
        </p>

        <div class="flex items-center gap-1">
            {{-- Sebelumnya --}}
            @if ($paginator->onFirstPage())
                <span class="pointer-events-none select-none rounded-[4px] border border-transparent px-2.5 py-1 font-mono text-[10px] font-semibold uppercase tracking-wide text-faint/50">&larr; Prev</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Halaman sebelumnya"
                    class="rounded-[4px] border border-transparent px-2.5 py-1 font-mono text-[10px] font-semibold uppercase tracking-wide text-stamp-700 transition hover:bg-stamp-50 hover:text-stamp-900">&larr; Prev</a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-1 font-mono text-[10px] text-faint">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page"
                                class="rounded-[4px] border border-stamp-600 bg-stamp-600 px-2.5 py-1 font-mono text-[11px] font-semibold tabular-nums text-white">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" aria-label="Ke halaman {{ $page }}"
                                class="rounded-[4px] border border-rule bg-sheet px-2.5 py-1 font-mono text-[11px] tabular-nums text-muted transition hover:bg-paper hover:text-stamp-700">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Berikutnya --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Halaman berikutnya"
                    class="rounded-[4px] border border-transparent px-2.5 py-1 font-mono text-[10px] font-semibold uppercase tracking-wide text-stamp-700 transition hover:bg-stamp-50 hover:text-stamp-900">Next &rarr;</a>
            @else
                <span class="pointer-events-none select-none rounded-[4px] border border-transparent px-2.5 py-1 font-mono text-[10px] font-semibold uppercase tracking-wide text-faint/50">Next &rarr;</span>
            @endif
        </div>
    </nav>
@elseif ($paginator->total() > 0)
    <p class="font-mono text-[10px] uppercase tracking-[0.14em] text-faint">
        Menampilkan {{ $paginator->firstItem() }}&ndash;{{ $paginator->lastItem() }} dari {{ $paginator->total() }} data
    </p>
@endif
