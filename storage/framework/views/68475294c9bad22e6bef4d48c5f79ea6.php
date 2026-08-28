<?php $__env->startSection('title', 'Tiket Berhasil Dibuat'); ?>

<?php $__env->startSection('content'); ?>
    <h1 class="sr-only">Laporan terkirim — nomor tiket Anda</h1>

    <p class="eyebrow mb-5 text-center">Laporan Anda sudah tercatat di sistem</p>

    <div class="relative mx-auto max-w-md">
        
        <div class="relative z-10 h-3.5 rounded-full bg-ink shadow-[var(--shadow-card-sm)]" aria-hidden="true">
            <div class="absolute left-1/2 top-1/2 h-1 w-28 -translate-x-1/2 -translate-y-1/2 rounded-full bg-white/20"></div>
        </div>

        <div class="relative z-0 -mt-0.5 overflow-hidden pt-0.5">
            <div class="stub-anim tear-bottom sheet mt-[-2px] px-6 pt-7 sm:px-8">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="eyebrow text-faint">Bukti pelaporan masalah</p>
                        <p class="mt-2 font-mono text-xs uppercase tracking-wider text-muted"><?php echo e($ticket->created_at->translatedFormat('d M Y · H.i')); ?></p>
                    </div>
                    <span class="flex h-9 items-center rounded-[4px] bg-ink px-2.5 font-mono text-[11px] font-semibold uppercase tracking-[0.14em] text-signal-400">
                        No. Tiket
                    </span>
                </div>

                <p class="select-all break-all mt-5 font-mono text-[27px] font-semibold leading-tight tracking-tight text-ink sm:text-[32px]">
                    <?php echo e($ticket->ticket_number); ?>

                </p>

                <div class="mt-6 grid grid-cols-2 gap-x-5 gap-y-4 border-t border-dashed border-rule pt-5">
                    <div>
                        <dt class="font-mono text-[9px] uppercase tracking-[0.18em] text-faint">Pelapor</dt>
                        <dd class="mt-1 truncate text-sm font-semibold text-ink"><?php echo e($ticket->requester_name); ?></dd>
                    </div>
                    <div>
                        <dt class="font-mono text-[9px] uppercase tracking-[0.18em] text-faint">Departemen</dt>
                        <dd class="mt-1 truncate text-sm font-semibold text-ink"><?php echo e($ticket->department ?? '—'); ?></dd>
                    </div>
                    <div>
                        <dt class="font-mono text-[9px] uppercase tracking-[0.18em] text-faint">Kategori</dt>
                        <dd class="mt-1 truncate text-sm font-semibold text-ink"><?php echo e($ticket->category?->category_name); ?></dd>
                    </div>
                    <div>
                        <dt class="font-mono text-[9px] uppercase tracking-[0.18em] text-faint">Urgensi</dt>
                        <dd class="mt-1"><?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2ddbc40e602c342e508ac696e52f8719 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge','data' => ['value' => $ticket->priority,'size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($ticket->priority),'size' => 'sm']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $attributes = $__attributesOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $component = $__componentOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__componentOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?></dd>
                    </div>
                    <div>
                        <dt class="font-mono text-[9px] uppercase tracking-[0.18em] text-faint">Status</dt>
                        <dd class="mt-1"><?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2ddbc40e602c342e508ac696e52f8719 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge','data' => ['value' => $ticket->status,'size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($ticket->status),'size' => 'sm']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $attributes = $__attributesOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $component = $__componentOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__componentOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?></dd>
                    </div>
                    <div class="col-span-2">
                        <dt class="font-mono text-[9px] uppercase tracking-[0.18em] text-faint">Target Penyelesaian (SLA)</dt>
                        <dd class="mt-1 font-mono text-sm font-semibold tabular-nums text-ink">
                            <?php echo e($ticket->sla_due_at->format('d M Y, H.i')); ?> WIB
                        </dd>
                    </div>
                </div>

                <div class="relative mt-7 border-t border-dashed border-rule pt-6">
                    <span class="absolute -top-2 left-1/2 -translate-x-1/2 bg-sheet px-3 font-mono text-[9px] uppercase tracking-[0.22em] text-faint">sobek di sini</span>

                    <?php
                        $bars = collect(str_split($ticket->ticket_number))
                            ->map(fn ($ch, $i) => (object) [
                                'w' => 1 + (ord($ch) + $i * 3) % 3,
                                'g' => 1 + (ord($ch) * ($i + 5)) % 4,
                            ]);
                    ?>
                    <div class="barcode mx-auto max-w-[240px] text-ink" aria-hidden="true">
                        <?php $__currentLoopData = $bars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span style="width: <?php echo e($bar->w); ?>px; margin-right: <?php echo e($bar->g); ?>px"></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <p class="mt-2 text-center font-mono text-[10px] tracking-[0.35em] text-muted"><?php echo e($ticket->ticket_number); ?></p>
                </div>
            </div>
        </div>

        
        <span class="stamp-anim stamp-mark absolute -top-10 right-0 z-20 -rotate-[5deg] border-stamp-600 bg-sheet/80 text-stamp-600 mix-blend-multiply sm:-right-6">
            Tercatat
        </span>
    </div>

    <div class="mx-auto mt-8 flex max-w-md flex-col gap-3 sm:flex-row">
        <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['href' => ''.e(route('tickets.check', ['number' => $ticket->ticket_number])).'','variant' => 'dark','class' => 'w-full sm:w-auto sm:flex-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('tickets.check', ['number' => $ticket->ticket_number])).'','variant' => 'dark','class' => 'w-full sm:w-auto sm:flex-1']); ?>
            Cek Status Tiket Ini
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['href' => ''.e(route('tickets.create')).'','variant' => 'secondary','class' => 'w-full sm:w-auto']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('tickets.create')).'','variant' => 'secondary','class' => 'w-full sm:w-auto']); ?>
            Buat Tiket Baru
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
    </div>

    <p class="mx-auto mt-5 max-w-md text-center text-xs leading-relaxed text-faint">
        Simpan nomor tiket di atas untuk memantau status penanganan kapan saja — tanpa perlu menghubungi IT langsung.
    </p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\99. Other\PROJECT\project-app\ticket-it-app\resources\views/public/tickets/success.blade.php ENDPATH**/ ?>