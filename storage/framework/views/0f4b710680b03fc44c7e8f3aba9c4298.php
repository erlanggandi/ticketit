<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['name', 'options' => [], 'selected' => null, 'label' => null, 'hint' => null, 'placeholder' => null, 'required' => false]));

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

foreach (array_filter((['name', 'options' => [], 'selected' => null, 'label' => null, 'hint' => null, 'placeholder' => null, 'required' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
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
        <select <?php echo e($attributes->merge([
            'id' => $name,
            'name' => $name,
            'class' => 'field appearance-none pr-9 '.($hasError ? 'field-error' : '').' '.(($selected === null || $selected === '') ? ' text-faint' : ''),
        ])); ?>>
            <?php if($placeholder !== null): ?>
                <option value="" <?php if($selected === null || $selected === ''): echo 'selected'; endif; ?>><?php echo e($placeholder); ?></option>
            <?php endif; ?>

            <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $optionLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(is_object($optionLabel)): ?>
                    <optgroup label="<?php echo e($key); ?>">
                        <?php $__currentLoopData = (array) $optionLabel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupKey => $groupLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($groupKey); ?>" <?php if((string) $selected === (string) $groupKey): echo 'selected'; endif; ?>><?php echo e($groupLabel); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </optgroup>
                <?php else: ?>
                    <option value="<?php echo e($key); ?>" <?php if((string) $selected === (string) $key): echo 'selected'; endif; ?>><?php echo e($optionLabel); ?></option>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        <svg class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
        </svg>
    </div>

    <?php if($hasError): ?>
        <p class="mt-1.5 font-mono text-[11px] uppercase tracking-wide text-critical-600"><?php echo e($errors->first($name)); ?></p>
    <?php elseif($hint): ?>
        <p class="mt-1.5 text-xs leading-relaxed text-faint"><?php echo e($hint); ?></p>
    <?php endif; ?>
</div>
<?php /**PATH D:\99. Other\PROJECT\project-app\ticket-it-app\resources\views/components/select.blade.php ENDPATH**/ ?>