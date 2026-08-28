@props(['name', 'options' => [], 'selected' => null, 'label' => null, 'hint' => null, 'placeholder' => null, 'required' => false])

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
        <select {{ $attributes->merge([
            'id' => $name,
            'name' => $name,
            'class' => 'field appearance-none pr-9 '.($hasError ? 'field-error' : '').' '.(($selected === null || $selected === '') ? ' text-faint' : ''),
        ]) }}>
            @if ($placeholder !== null)
                <option value="" @selected($selected === null || $selected === '')>{{ $placeholder }}</option>
            @endif

            @foreach ($options as $key => $optionLabel)
                @if (is_object($optionLabel))
                    <optgroup label="{{ $key }}">
                        @foreach ((array) $optionLabel as $groupKey => $groupLabel)
                            <option value="{{ $groupKey }}" @selected((string) $selected === (string) $groupKey)>{{ $groupLabel }}</option>
                        @endforeach
                    </optgroup>
                @else
                    <option value="{{ $key }}" @selected((string) $selected === (string) $key)>{{ $optionLabel }}</option>
                @endif
            @endforeach
        </select>

        <svg class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
        </svg>
    </div>

    @if ($hasError)
        <p class="mt-1.5 font-mono text-[11px] uppercase tracking-wide text-critical-600">{{ $errors->first($name) }}</p>
    @elseif ($hint)
        <p class="mt-1.5 text-xs leading-relaxed text-faint">{{ $hint }}</p>
    @endif
</div>
