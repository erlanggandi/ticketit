<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['name', 'label' => null, 'hint' => null, 'required' => false, 'leadingIcon' => null]));

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

foreach (array_filter((['name', 'label' => null, 'hint' => null, 'required' => false, 'leadingIcon' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
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

    <div class="relative">
        <?php if($leadingIcon): ?>
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-faint">
                <?php echo e($leadingIcon); ?>

            </div>
        <?php endif; ?>

        <input <?php echo e($attributes->merge([
            'id' => $name,
            'name' => $name,
            'type' => 'text',
            'class' => 'field '.($hasError ? 'field-error' : '').($leadingIcon ? ' pl-9.5' : ''),
        ])); ?>>
    </div>

    <?php if($hasError): ?>
        <p class="mt-1.5 font-mono text-[11px] uppercase tracking-wide text-critical-600"><?php echo e($errors->first($name)); ?></p>
    <?php elseif($hint): ?>
        <p class="mt-1.5 text-xs leading-relaxed text-faint"><?php echo e($hint); ?></p>
    <?php endif; ?>
</div>
<?php /**PATH D:\99. Other\PROJECT\project-app\ticket-it-app\resources\views/components/input.blade.php ENDPATH**/ ?>