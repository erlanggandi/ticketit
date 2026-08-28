@props(['name', 'label' => null, 'hint' => null, 'rows' => 4, 'required' => false])

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

    <textarea {{ $attributes->merge([
        'id' => $name,
        'name' => $name,
        'rows' => $rows,
        'class' => 'field '.($hasError ? 'field-error' : ''),
    ]) }}>{{ old($name, $attributes->get('value')) }}</textarea>

    @if ($hasError)
        <p class="mt-1.5 font-mono text-[11px] uppercase tracking-wide text-critical-600">{{ $errors->first($name) }}</p>
    @elseif ($hint)
        <p class="mt-1.5 text-xs leading-relaxed text-faint">{{ $hint }}</p>
    @endif
</div>
