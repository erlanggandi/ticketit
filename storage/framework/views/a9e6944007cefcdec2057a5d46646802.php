<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title' => null, 'padded' => true]));

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

foreach (array_filter((['title' => null, 'padded' => true]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div <?php echo e($attributes->merge(['class' => 'sheet overflow-hidden'])); ?>>
    <?php if($title): ?>
        <div class="flex items-center justify-between border-b border-ink px-5 py-3 sm:px-6">
            <h3 class="font-mono text-[11px] font-semibold uppercase tracking-[0.16em] text-ink"><?php echo e($title); ?></h3>
            <span class="h-[3px] w-8 bg-ink" aria-hidden="true"></span>
        </div>
    <?php endif; ?>
    <div class="<?php echo e($padded ? 'p-5 sm:p-6' : ''); ?>">
        <?php echo e($slot); ?>

    </div>
</div>
<?php /**PATH /var/www/html/resources/views/components/card.blade.php ENDPATH**/ ?>