@props(['href', 'label', 'active' => false])

<a href="{{ $href }}" aria-current="{{ $active ? 'page' : false }}" {{ $attributes->merge([
    'class' => 'group relative flex items-center gap-3 border-l-[3px] px-4 py-2 text-sm transition '
        .($active
            ? 'border-signal-500 bg-white/[0.07] font-semibold text-white'
            : 'border-transparent font-medium text-paper/60 hover:bg-white/[0.04] hover:text-paper'),
]) }}>
    <span class="{{ $active ? 'text-signal-400' : 'text-paper/40 group-hover:text-paper/70' }}">
        {{ $slot }}
    </span>
    {{ $label }}
</a>
