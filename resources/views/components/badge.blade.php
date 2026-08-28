@props(['value', 'size' => 'md'])

@php
    // Warna dingin = siklus hidup tiket; warna hangat = urgensi.
    $palettes = [
        'Open' => 'border-stamp-500/70 bg-stamp-50/70 text-stamp-800',
        'In Progress' => 'border-stamp-600 bg-stamp-600 text-white',
        'Resolved' => 'border-ok-500/60 bg-ok-50/80 text-ok-700',
        'Closed' => 'border-ink/25 bg-ink/[0.06] text-muted',

        'Low' => 'border-ink/20 bg-transparent text-faint',
        'Medium' => 'border-ink/35 bg-sheet text-ink',
        'High' => 'border-signal-600 bg-signal-50/90 text-signal-700',
        'Critical' => 'border-critical-600 bg-critical-500 text-white',
    ];

    $style = $palettes[$value] ?? 'border-ink/25 bg-ink/[0.06] text-muted';
    $padding = $size === 'sm' ? 'px-1.5 py-0' : 'px-2 py-0.5';
@endphp

<span {{ $attributes->merge(['class' => 'chip '.$padding.' '.$style]) }}>
    @if (in_array($value, ['In Progress', 'High', 'Critical']))
        <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-current"></span>
    @endif
    {{ $value }}
</span>
