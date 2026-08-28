<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['name', 'label' => null, 'hint' => null, 'rows' => 4, 'required' => false]));

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

foreach (array_filter((['name', 'label' => null, 'hint' => null, 'rows' => 4, 'required' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $hasError = $errors?->has($name);
?>

<div>
    <?php if($label): ?>
        <label for="<?php echo e($name); ?>" class="label">
            <?php echo e($label); ?>

            <?php if($required): ?>
                <span class="text-critical-500" aria-hidden="true">*</span>
            <?php endif; ?>
        </label>
    <?php endif; ?>

    <textarea <?php echo e($attributes->merge([
        'id' => $name,
        'name' => $name,
        'rows' => $rows,
        'class' => 'field '.($hasError ? 'field-error' : ''),
    ])); ?>><?php echo e(old($name, $attributes->get('value'))); ?></textarea>

    <?php if($hasError): ?>
        <p class="mt-1.5 font-mono text-[11px] uppercase tracking-wide text-critical-600"><?php echo e($errors->first($name)); ?></p>
    <?php elseif($hint): ?>
        <p class="mt-1.5 text-xs leading-relaxed text-faint"><?php echo e($hint); ?></p>
    <?php endif; ?>
</div>
<?php /**PATH /var/www/html/resources/views/components/textarea.blade.php ENDPATH**/ ?>