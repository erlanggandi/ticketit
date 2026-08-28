@props(['title' => null, 'padded' => true])

<div {{ $attributes->merge(['class' => 'sheet overflow-hidden']) }}>
    @if ($title)
        <div class="flex items-center justify-between border-b border-ink px-5 py-3 sm:px-6">
            <h3 class="font-mono text-[11px] font-semibold uppercase tracking-[0.16em] text-ink">{{ $title }}</h3>
            <span class="h-[3px] w-8 bg-ink" aria-hidden="true"></span>
        </div>
    @endif
    <div class="{{ $padded ? 'p-5 sm:p-6' : '' }}">
        {{ $slot }}
    </div>
</div>
