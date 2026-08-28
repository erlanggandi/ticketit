<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title', 'tone' => 'brand', 'value' => '']));

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

foreach (array_filter((['title', 'tone' => 'brand', 'value' => '']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    // Aksen kiri = makna domain: biru sistem, oker proses, hijau selesai, merah genting.
    $tones = [
        'brand' => ['border-l-stamp-600', 'text-stamp-600'],
        'amber' => ['border-l-signal-500', 'text-signal-500'],
        'emerald' => ['border-l-ok-500', 'text-ok-500'],
        'red' => ['border-l-critical-500', 'text-critical-500'],
        'slate' => ['border-l-ink/40', 'text-muted'],
    ];

    [$accent, $iconStroke] = $tones[$tone] ?? $tones['brand'];
?>

<div <?php echo e($attributes->merge(['class' => 'sheet-flat border-l-4 p-5 '.$accent])); ?>>
    <div class="flex items-start justify-between gap-3">
        <div>
            <p class="font-mono text-[10px] font-semibold uppercase tracking-[0.16em] text-muted"><?php echo e($title); ?></p>
            <p class="mt-2 font-mono text-[26px] font-semibold leading-none tracking-tight text-ink tabular-nums">
                <?php echo e($value); ?>

            </p>
            <?php if(isset($meta)): ?>
                <p class="mt-1.5 text-xs text-faint"><?php echo e($meta); ?></p>
            <?php endif; ?>
        </div>
        <?php if(isset($icon)): ?>
            <div class="rounded-[4px] border border-rule bg-paper p-2">
                <svg class="h-4.5 w-4.5 <?php echo e($iconStroke); ?>" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <?php echo e($icon); ?>

                </svg>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH D:\99. Other\PROJECT\project-app\ticket-it-app\resources\views/components/stat-card.blade.php ENDPATH**/ ?>