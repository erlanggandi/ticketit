@props(['name', 'label' => null, 'hint' => null, 'required' => false, 'leadingIcon' => null])

@php
    $hasError = $errors?->has($name);
@endphp

<div>
    @if ($label)
        <label for="{{ $name }}" class="label">
            {{ $label }}
            @if ($required)
                <span class="text-critical-500" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        @if ($leadingIcon)
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-faint">
                {{ $leadingIcon }}
            </div>
        @endif

        <input {{ $attributes->merge([
            'id' => $name,
            'name' => $name,
            'type' => 'text',
            'class' => 'field '.($hasError ? 'field-error' : '').($leadingIcon ? ' pl-9.5' : ''),
        ]) }}>
    </div>

    @if ($hasError)
        <p class="mt-1.5 font-mono text-[11px] uppercase tracking-wide text-critical-600">{{ $errors->first($name) }}</p>
    @elseif ($hint)
        <p class="mt-1.5 text-xs leading-relaxed text-faint">{{ $hint }}</p>
    @endif
</div>
