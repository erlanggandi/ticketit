@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
])

@php
    $variants = [
        'primary' => 'btn-primary',
        'secondary' => 'btn-secondary',
        'danger' => 'btn-danger',
        'ghost' => 'border-transparent bg-transparent font-semibold text-stamp-700 shadow-none hover:text-stamp-800 hover:underline',
        'dark' => 'btn-dark',
    ];

    $sizes = [
        'sm' => 'btn-sm',
        'md' => 'btn-md',
        'lg' => 'btn-lg',
    ];

    $classes = 'btn '.$variants[$variant].' '.($variant === 'ghost' ? '' : $sizes[$size]);
@endphp

@if (($href ?? null))
    <a {{ $attributes->merge(['href' => $href, 'class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['type' => $type, 'class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
