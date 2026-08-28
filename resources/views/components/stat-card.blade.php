@props(['title', 'tone' => 'brand', 'value' => ''])

@php
    // Aksen kiri = makna domain: biru sistem, oker proses, hijau selesai, merah genting.
    $tones = [
        'brand' => ['border-l-stamp-600', 'text-stamp-600'],
        'amber' => ['border-l-signal-500', 'text-signal-500'],
        'emerald' => ['border-l-ok-500', 'text-ok-500'],
        'red' => ['border-l-critical-500', 'text-critical-500'],
        'slate' => ['border-l-ink/40', 'text-muted'],
    ];

    [$accent, $iconStroke] = $tones[$tone] ?? $tones['brand'];
@endphp

<div {{ $attributes->merge(['class' => 'sheet-flat border-l-4 p-5 '.$accent]) }}>
    <div class="flex items-start justify-between gap-3">
        <div>
            <p class="font-mono text-[10px] font-semibold uppercase tracking-[0.16em] text-muted">{{ $title }}</p>
            <p class="mt-2 font-mono text-[26px] font-semibold leading-none tracking-tight text-ink tabular-nums">
                {{ $value }}
            </p>
            @isset($meta)
                <p class="mt-1.5 text-xs text-faint">{{ $meta }}</p>
            @endisset
        </div>
        @isset($icon)
            <div class="rounded-[4px] border border-rule bg-paper p-2">
                <svg class="h-4.5 w-4.5 {{ $iconStroke }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    {{ $icon }}
                </svg>
            </div>
        @endisset
    </div>
</div>
