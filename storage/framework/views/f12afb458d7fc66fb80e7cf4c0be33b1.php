<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['href', 'label', 'active' => false]));

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

foreach (array_filter((['href', 'label', 'active' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<a href="<?php echo e($href); ?>" aria-current="<?php echo e($active ? 'page' : false); ?>" <?php echo e($attributes->merge([
    'class' => 'group relative flex items-center gap-3 border-l-[3px] px-4 py-2 text-sm transition '
        .($active
            ? 'border-signal-500 bg-white/[0.07] font-semibold text-white'
            : 'border-transparent font-medium text-paper/60 hover:bg-white/[0.04] hover:text-paper'),
])); ?>>
    <span class="<?php echo e($active ? 'text-signal-400' : 'text-paper/40 group-hover:text-paper/70'); ?>">
        <?php echo e($slot); ?>

    </span>
    <?php echo e($label); ?>

</a>
<?php /**PATH D:\99. Other\PROJECT\project-app\ticket-it-app\resources\views/components/nav-item.blade.php ENDPATH**/ ?>