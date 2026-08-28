<?php $__env->startSection('title', 'Detail Tiket'); ?>
<?php $__env->startSection('page_title', $ticket->ticket_number); ?>
<?php $__env->startSection('page_subtitle', 'Detail dan penanganan tiket'); ?>

<?php $__env->startSection('content'); ?>
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        <div class="space-y-6 xl:col-span-2">
            <?php if (isset($component)) { $__componentOriginal53747ceb358d30c0105769f8471417f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53747ceb358d30c0105769f8471417f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <p class="eyebrow text-faint">Nomor tiket</p>
                        <p class="mt-1 select-all font-mono text-xl font-semibold tracking-tight text-ink"><?php echo e($ticket->ticket_number); ?></p>
                        <p class="mt-1 font-mono text-[10px] uppercase tracking-wider text-muted">Dibuat <?php echo e($ticket->created_at->format('d M Y, H.i')); ?></p>
                    </div>
                    <div class="flex items-center gap-2 pt-1">
                        <?php if($ticket->isOverdue()): ?>
                            <span class="chip border-critical-500 bg-critical-500 text-white">Melewati SLA</span>
                        <?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2ddbc40e602c342e508ac696e52f8719 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge','data' => ['value' => $ticket->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($ticket->status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $attributes = $__attributesOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $component = $__componentOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__componentOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
                    </div>
                </div>

                <dl class="mt-5 grid grid-cols-2 gap-x-6 gap-y-4 border-t border-dashed border-rule pt-5 sm:grid-cols-5">
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
                        <dt class="font-mono text-[9px] uppercase tracking-[0.18em] text-faint">Prioritas</dt>
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
                        <dt class="font-mono text-[9px] uppercase tracking-[0.18em] text-faint">SLA Due</dt>
                        <dd class="mt-1 font-mono text-sm font-semibold tabular-nums <?php echo e($ticket->isOverdue() ? 'text-critical-600' : 'text-ink'); ?>">
                            <?php echo e($ticket->sla_due_at->format('d M, H.i')); ?>

                        </dd>
                    </div>
                </dl>

                <div class="mt-5 rounded-[5px] border border-dashed border-rule bg-paper/60 p-4">
                    <dt class="font-mono text-[9px] uppercase tracking-[0.18em] text-muted">Deskripsi Masalah</dt>
                    <p class="mt-2 whitespace-pre-line text-sm leading-relaxed text-ink"><?php echo e($ticket->description); ?></p>
                </div>

                <?php if($ticket->resolved_at || $ticket->closed_at): ?>
                    <div class="mt-5 flex flex-wrap items-center gap-x-8 gap-y-2 border-t border-dashed border-rule pt-4 font-mono text-xs tabular-nums">
                        <?php if($ticket->resolved_at): ?>
                            <span class="text-muted">Resolved: <strong class="text-ok-700"><?php echo e($ticket->resolved_at->format('d M Y, H.i')); ?></strong></span>
                            <?php ($compliant = $ticket->slaCompliant()); ?>
                            <span class="<?php echo e($compliant ? 'text-ok-600' : 'text-critical-600'); ?> font-semibold uppercase tracking-wide">
                                <?php echo e($compliant ? 'Sesuai SLA' : 'Melebihi target SLA ('.$ticket->created_at->diffInHours($ticket->resolved_at).' jam)'); ?>

                            </span>
                        <?php endif; ?>
                        <?php if($ticket->closed_at): ?>
                            <span class="text-muted">Closed: <strong class="text-ink"><?php echo e($ticket->closed_at->format('d M Y, H.i')); ?></strong></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53747ceb358d30c0105769f8471417f6)): ?>
<?php $attributes = $__attributesOriginal53747ceb358d30c0105769f8471417f6; ?>
<?php unset($__attributesOriginal53747ceb358d30c0105769f8471417f6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53747ceb358d30c0105769f8471417f6)): ?>
<?php $component = $__componentOriginal53747ceb358d30c0105769f8471417f6; ?>
<?php unset($__componentOriginal53747ceb358d30c0105769f8471417f6); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal53747ceb358d30c0105769f8471417f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53747ceb358d30c0105769f8471417f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card','data' => ['title' => 'Riwayat Aktivitas (Audit Trail)']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Riwayat Aktivitas (Audit Trail)']); ?>
                <?php $__empty_1 = true; $__currentLoopData = $trail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="relative pb-5 pl-7 last:pb-0">
                        <span class="absolute left-0 top-1 flex h-3.5 w-3.5 items-center justify-center rounded-[2px] ring-4 ring-sheet
                            <?php echo e(str_contains($log->action, 'status') ? 'bg-stamp-600' : 'bg-rule'); ?>"></span>
                        <span class="absolute left-[6.5px] top-5 h-full w-px bg-rule last:hidden"></span>
                        <p class="font-mono text-[13px] font-semibold capitalize text-ink"><?php echo e(str_replace('_', ' ', $log->action)); ?>

                            <span class="ml-1 text-[10px] font-normal normal-case tracking-wide text-faint"><?php echo e($log->created_at->format('d M Y, H:i')); ?></span>
                        </p>
                        <p class="text-xs text-muted">oleh <?php echo e($log->actor_name ?? 'System'); ?></p>

                        <?php if($log->old_values && $log->new_values && isset($log->old_values['status'])): ?>
                            <p class="mt-1 font-mono text-xs text-muted"><?php echo e($log->old_values['status']); ?> &rarr; <strong class="text-ink"><?php echo e($log->new_values['status']); ?></strong></p>
                        <?php elseif($log->new_values && isset($log->new_values['priority'])): ?>
                            <p class="mt-1 font-mono text-xs text-muted">Prioritas: <?php echo e($log->old_values['priority'] ?? '-'); ?> &rarr; <strong class="text-ink"><?php echo e($log->new_values['priority']); ?></strong></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <?php if (isset($component)) { $__componentOriginal074a021b9d42f490272b5eefda63257c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal074a021b9d42f490272b5eefda63257c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.empty-state','data' => ['title' => 'Belum ada aktivitas']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Belum ada aktivitas']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal074a021b9d42f490272b5eefda63257c)): ?>
<?php $attributes = $__attributesOriginal074a021b9d42f490272b5eefda63257c; ?>
<?php unset($__attributesOriginal074a021b9d42f490272b5eefda63257c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal074a021b9d42f490272b5eefda63257c)): ?>
<?php $component = $__componentOriginal074a021b9d42f490272b5eefda63257c; ?>
<?php unset($__componentOriginal074a021b9d42f490272b5eefda63257c); ?>
<?php endif; ?>
                <?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53747ceb358d30c0105769f8471417f6)): ?>
<?php $attributes = $__attributesOriginal53747ceb358d30c0105769f8471417f6; ?>
<?php unset($__attributesOriginal53747ceb358d30c0105769f8471417f6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53747ceb358d30c0105769f8471417f6)): ?>
<?php $component = $__componentOriginal53747ceb358d30c0105769f8471417f6; ?>
<?php unset($__componentOriginal53747ceb358d30c0105769f8471417f6); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal53747ceb358d30c0105769f8471417f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53747ceb358d30c0105769f8471417f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card','data' => ['title' => 'Ubah Status']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Ubah Status']); ?>
                <?php if($ticket->isClosed()): ?>
                    <div class="flex items-start gap-3 rounded-[5px] border border-dashed border-rule bg-paper/60 p-4 text-sm leading-relaxed text-muted">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-faint" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                        <span>Tiket ini sudah <strong class="text-ink">Closed</strong> dan tidak dapat diubah lagi sesuai aturan bisnis.</span>
                    </div>
                <?php else: ?>
                    <form method="POST" action="<?php echo e(route('admin.tickets.updateStatus', $ticket)); ?>" data-disable-on-submit>
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>

                        <div class="mb-3 flex items-center justify-between rounded-[4px] border border-stamp-200 bg-stamp-50 px-3 py-2 font-mono text-[10px] font-medium uppercase tracking-wide text-stamp-800">
                            <span>Alur: Open &rarr; In Progress &rarr; Resolved &rarr; Closed</span>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <?php $__currentLoopData = $nextStatuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $next): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <button type="submit" name="status" value="<?php echo e($next); ?>"
                                    class="btn btn-sm <?php echo e($next === 'Resolved' ? 'btn-dark border-ok-600 bg-ok-500 hover:bg-ok-600' : ($next === 'Closed' ? 'btn-dark' : ($next === 'In Progress' ? 'btn-primary' : 'btn-secondary'))); ?>">
                                    <?php echo e($next === 'In Progress' ? 'Mulai Kerjakan' : $next); ?>

                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                                </button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if(empty($nextStatuses)): ?>
                                <p class="text-sm text-faint">Tidak ada transisi tersedia.</p>
                            <?php endif; ?>
                        </div>
                    </form>
                <?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53747ceb358d30c0105769f8471417f6)): ?>
<?php $attributes = $__attributesOriginal53747ceb358d30c0105769f8471417f6; ?>
<?php unset($__attributesOriginal53747ceb358d30c0105769f8471417f6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53747ceb358d30c0105769f8471417f6)): ?>
<?php $component = $__componentOriginal53747ceb358d30c0105769f8471417f6; ?>
<?php unset($__componentOriginal53747ceb358d30c0105769f8471417f6); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal53747ceb358d30c0105769f8471417f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53747ceb358d30c0105769f8471417f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card','data' => ['title' => 'Tim Teknis & Eskalasi']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Tim Teknis & Eskalasi']); ?>
                <?php ($activeAssignment = $ticket->assignments->first(fn ($a) => $a->unassigned_at === null)); ?>

                <?php if($ticket->isClosed()): ?>
                    <p class="font-mono text-xs uppercase tracking-wide text-faint">Tidak dapat diubah — tiket Closed.</p>
                <?php else: ?>
                    <form method="POST" action="<?php echo e(route('admin.tickets.assign', $ticket)); ?>" data-disable-on-submit class="space-y-3">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>

                        <div class="flex items-center justify-between rounded-[4px] border border-rule bg-paper/60 px-3 py-2">
                            <span class="font-mono text-[10px] uppercase tracking-wide text-muted">Penanggung jawab saat ini</span>
                            <?php if($activeAssignment): ?>
                                <span class="chip border-ok-500/60 bg-ok-50 text-ok-700"><?php echo e($activeAssignment->technician?->technician_name); ?></span>
                            <?php else: ?>
                                <span class="font-mono text-[10px] uppercase tracking-wide text-faint">Belum ditugaskan</span>
                            <?php endif; ?>
                        </div>

                        <?php if (isset($component)) { $__componentOriginaled2cde6083938c436304f332ba96bb7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaled2cde6083938c436304f332ba96bb7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.select','data' => ['name' => 'technician_id','label' => 'Tugaskan / Eskalasi ke teknisi','required' => true,'options' => $technicians->pluck('technician_name', 'id'),'placeholder' => '— Pilih teknisi aktif —','hint' => 'Menugaskan ulang akan menutup riwayat penanganan sebelumnya.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'technician_id','label' => 'Tugaskan / Eskalasi ke teknisi','required' => true,'options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($technicians->pluck('technician_name', 'id')),'placeholder' => '— Pilih teknisi aktif —','hint' => 'Menugaskan ulang akan menutup riwayat penanganan sebelumnya.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaled2cde6083938c436304f332ba96bb7c)): ?>
<?php $attributes = $__attributesOriginaled2cde6083938c436304f332ba96bb7c; ?>
<?php unset($__attributesOriginaled2cde6083938c436304f332ba96bb7c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaled2cde6083938c436304f332ba96bb7c)): ?>
<?php $component = $__componentOriginaled2cde6083938c436304f332ba96bb7c; ?>
<?php unset($__componentOriginaled2cde6083938c436304f332ba96bb7c); ?>
<?php endif; ?>

                        <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['type' => 'submit','size' => 'sm','variant' => 'secondary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','size' => 'sm','variant' => 'secondary']); ?>
                            <?php echo e($activeAssignment ? 'Eskalasi ke Teknisi Terpilih' : 'Tugaskan Sekarang'); ?>

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
                    </form>

                    <?php if($ticket->assignments->isNotEmpty()): ?>
                        <div class="border-t border-dashed border-rule pt-4">
                            <p class="eyebrow mb-2">Riwayat penanganan</p>
                            <ul class="space-y-1.5 font-mono text-xs tabular-nums text-muted">
                                <?php $__currentLoopData = $ticket->assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="flex items-center justify-between gap-3">
                                        <span class="font-semibold text-ink"><?php echo e($assignment->technician?->technician_name ?? '(teknisi dihapus)'); ?></span>
                                        <span class="text-faint"><?php echo e($assignment->assigned_at->format('d M H.i')); ?> &rarr; <?php echo e($assignment->unassigned_at?->format('d M H.i') ?? 'sekarang'); ?></span>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53747ceb358d30c0105769f8471417f6)): ?>
<?php $attributes = $__attributesOriginal53747ceb358d30c0105769f8471417f6; ?>
<?php unset($__attributesOriginal53747ceb358d30c0105769f8471417f6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53747ceb358d30c0105769f8471417f6)): ?>
<?php $component = $__componentOriginal53747ceb358d30c0105769f8471417f6; ?>
<?php unset($__componentOriginal53747ceb358d30c0105769f8471417f6); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal53747ceb358d30c0105769f8471417f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53747ceb358d30c0105769f8471417f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card','data' => ['title' => 'Override Prioritas & Kategori']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Override Prioritas & Kategori']); ?>
                <?php if($ticket->isClosed()): ?>
                    <p class="font-mono text-xs uppercase tracking-wide text-faint">Tidak dapat diubah — tiket Closed.</p>
                <?php else: ?>
                    <form method="POST" action="<?php echo e(route('admin.tickets.updateDetails', $ticket)); ?>" data-disable-on-submit class="space-y-4">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>

                        <?php if (isset($component)) { $__componentOriginaled2cde6083938c436304f332ba96bb7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaled2cde6083938c436304f332ba96bb7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.select','data' => ['name' => 'priority','label' => 'Prioritas','required' => true,'options' => array_combine($priorities, $priorities),'selected' => $ticket->priority,'hint' => 'Jika prioritas berubah, SLA due date dihitung ulang dari waktu tiket dibuat.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'priority','label' => 'Prioritas','required' => true,'options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(array_combine($priorities, $priorities)),'selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($ticket->priority),'hint' => 'Jika prioritas berubah, SLA due date dihitung ulang dari waktu tiket dibuat.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaled2cde6083938c436304f332ba96bb7c)): ?>
<?php $attributes = $__attributesOriginaled2cde6083938c436304f332ba96bb7c; ?>
<?php unset($__attributesOriginaled2cde6083938c436304f332ba96bb7c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaled2cde6083938c436304f332ba96bb7c)): ?>
<?php $component = $__componentOriginaled2cde6083938c436304f332ba96bb7c; ?>
<?php unset($__componentOriginaled2cde6083938c436304f332ba96bb7c); ?>
<?php endif; ?>

                        <?php if (isset($component)) { $__componentOriginaled2cde6083938c436304f332ba96bb7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaled2cde6083938c436304f332ba96bb7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.select','data' => ['name' => 'category_id','label' => 'Kategori','required' => true,'options' => $categories->pluck('category_name', 'id'),'selected' => $ticket->category_id]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'category_id','label' => 'Kategori','required' => true,'options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($categories->pluck('category_name', 'id')),'selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($ticket->category_id)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaled2cde6083938c436304f332ba96bb7c)): ?>
<?php $attributes = $__attributesOriginaled2cde6083938c436304f332ba96bb7c; ?>
<?php unset($__attributesOriginaled2cde6083938c436304f332ba96bb7c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaled2cde6083938c436304f332ba96bb7c)): ?>
<?php $component = $__componentOriginaled2cde6083938c436304f332ba96bb7c; ?>
<?php unset($__componentOriginaled2cde6083938c436304f332ba96bb7c); ?>
<?php endif; ?>

                        <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['type' => 'submit','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','size' => 'sm']); ?>Simpan Perubahan <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
                    </form>
                <?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53747ceb358d30c0105769f8471417f6)): ?>
<?php $attributes = $__attributesOriginal53747ceb358d30c0105769f8471417f6; ?>
<?php unset($__attributesOriginal53747ceb358d30c0105769f8471417f6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53747ceb358d30c0105769f8471417f6)): ?>
<?php $component = $__componentOriginal53747ceb358d30c0105769f8471417f6; ?>
<?php unset($__componentOriginal53747ceb358d30c0105769f8471417f6); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal53747ceb358d30c0105769f8471417f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53747ceb358d30c0105769f8471417f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card','data' => ['title' => 'Info SLA']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Info SLA']); ?>
                <ul class="space-y-2.5 font-mono text-[13px] tabular-nums">
                    <li class="flex justify-between gap-4"><span class="text-faint">Due date</span><span class="font-medium text-ink"><?php echo e($ticket->sla_due_at->format('d M Y, H.i')); ?></span></li>
                    <li class="flex justify-between gap-4">
                        <span class="text-faint">Sisa waktu</span>
                        <?php if($ticket->isActive()): ?>
                            <span class="font-semibold <?php echo e($ticket->isOverdue() ? 'text-critical-600' : 'text-ok-600'); ?>"><?php echo e(now()->diffForHumans($ticket->sla_due_at, ['parts' => 2])); ?></span>
                        <?php else: ?>
                            <span class="text-faint">selesai / ditutup</span>
                        <?php endif; ?>
                    </li>
                    <li class="flex justify-between gap-4"><span class="text-faint">Alert mendekati terkirim</span><span class="font-medium text-ink"><?php echo e($ticket->sla_alert_approaching_sent_at ? 'Ya' : 'Belum'); ?></span></li>
                    <li class="flex justify-between gap-4"><span class="text-faint">Alert breach terkirim</span><span class="font-medium text-ink"><?php echo e($ticket->sla_alert_breach_sent_at ? 'Ya' : 'Belum'); ?></span></li>
                </ul>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53747ceb358d30c0105769f8471417f6)): ?>
<?php $attributes = $__attributesOriginal53747ceb358d30c0105769f8471417f6; ?>
<?php unset($__attributesOriginal53747ceb358d30c0105769f8471417f6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53747ceb358d30c0105769f8471417f6)): ?>
<?php $component = $__componentOriginal53747ceb358d30c0105769f8471417f6; ?>
<?php unset($__componentOriginal53747ceb358d30c0105769f8471417f6); ?>
<?php endif; ?>
        </div>

    <div class="mt-6">
        <a href="<?php echo e(route('admin.tickets.index')); ?>" class="inline-flex items-center gap-1.5 font-mono text-[11px] font-semibold uppercase tracking-wide text-muted hover:text-ink">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Kembali ke daftar tiket
        </a>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\99. Other\PROJECT\project-app\ticket-it-app\resources\views/admin/tickets/show.blade.php ENDPATH**/ ?>