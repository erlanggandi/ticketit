@props([
    'type' => 'info',
])

@php
    $styles = match ($type) {
        'success' => 'border-ok-500 bg-ok-50 text-ok-700',
        'error', 'danger' => 'border-critical-500 bg-critical-50 text-critical-700',
        'warning' => 'border-signal-500 bg-signal-50 text-signal-700',
        default => 'border-stamp-500 bg-stamp-50 text-stamp-800',
    };
@endphp

<div {{ $attributes->merge(['class' => 'rounded-[5px] border-l-4 px-4 py-3 text-sm leading-relaxed '.$styles]) }} role="alert">
    {{ $slot }}
</div>
