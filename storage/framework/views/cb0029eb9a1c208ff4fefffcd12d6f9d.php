<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['id', 'title']));

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

foreach (array_filter((['id', 'title']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<dialog id="<?php echo e($id); ?>" class="max-w-md mx-auto rounded-md border border-ink bg-sheet p-0 shadow-[var(--shadow-pop)] backdrop:bg-ink/55">
    <div class="flex items-center justify-between gap-4 border-b border-ink px-6 py-3.5">
        <h3 class="font-mono text-[11px] font-semibold uppercase tracking-[0.16em] text-ink"><?php echo e($title); ?></h3>
        <button type="button" onclick="closeDialog('<?php echo e($id); ?>')" class="-m-1 rounded-[4px] p-1 text-muted transition hover:text-critical-600" aria-label="Tutup">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <div class="p-6">
        <?php echo e($slot); ?>

    </div>
</dialog>
<?php /**PATH D:\99. Other\PROJECT\project-app\ticket-it-app\resources\views/components/modal-dialog.blade.php ENDPATH**/ ?>