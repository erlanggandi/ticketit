<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['value', 'size' => 'md']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['value', 'size' => 'md']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
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
?>

<span <?php echo e($attributes->merge(['class' => 'chip '.$padding.' '.$style])); ?>>
    <?php if(in_array($value, ['In Progress', 'High', 'Critical'])): ?>
        <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-current"></span>
    <?php endif; ?>
    <?php echo e($value); ?>

</span>
<?php /**PATH D:\99. Other\PROJECT\project-app\ticket-it-app\resources\views/components/badge.blade.php ENDPATH**/ ?>