<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'type' => 'info',
]));

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

foreach (array_filter(([
    'type' => 'info',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $styles = match ($type) {
        'success' => 'border-ok-500 bg-ok-50 text-ok-700',
        'error', 'danger' => 'border-critical-500 bg-critical-50 text-critical-700',
        'warning' => 'border-signal-500 bg-signal-50 text-signal-700',
        default => 'border-stamp-500 bg-stamp-50 text-stamp-800',
    };
?>

<div <?php echo e($attributes->merge(['class' => 'rounded-[5px] border-l-4 px-4 py-3 text-sm leading-relaxed '.$styles])); ?> role="alert">
    <?php echo e($slot); ?>

</div>
<?php /**PATH D:\99. Other\PROJECT\project-app\ticket-it-app\resources\views/components/alert.blade.php ENDPATH**/ ?>