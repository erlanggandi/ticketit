<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
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
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
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
?>

<?php if(($href ?? null)): ?>
    <a <?php echo e($attributes->merge(['href' => $href, 'class' => $classes])); ?>>
        <?php echo e($slot); ?>

    </a>
<?php else: ?>
    <button <?php echo e($attributes->merge(['type' => $type, 'class' => $classes])); ?>>
        <?php echo e($slot); ?>

    </button>
<?php endif; ?>
<?php /**PATH D:\99. Other\PROJECT\project-app\ticket-it-app\resources\views/components/button.blade.php ENDPATH**/ ?>