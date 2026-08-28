<?php $__env->startSection('title', 'Daftar Tiket'); ?>
<?php $__env->startSection('page_title', 'Tiket'); ?>
<?php $__env->startSection('page_subtitle', 'Kelola dan pantau seluruh tiket masuk'); ?>

<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginal53747ceb358d30c0105769f8471417f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53747ceb358d30c0105769f8471417f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card','data' => ['padded' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['padded' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
        <form method="GET" action="<?php echo e(route('admin.tickets.index')); ?>" class="flex flex-wrap items-end gap-3 border-b border-dashed border-rule bg-paper/50 p-4">
            <div class="min-w-[200px] flex-1">
                <label class="label" for="filter-q">Cari</label>
                <input id="filter-q" type="text" name="q" value="<?php echo e($filters['q'] ?? ''); ?>" placeholder="No. tiket / nama pelapor…" class="field py-2" />
            </div>

            <div class="w-36">
                <label class="label" for="filter-status">Status</label>
                <select id="filter-status" name="status" class="field appearance-none py-2 pr-8">
                    <option value="">Semua</option>
                    <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s); ?>" <?php if(($filters['status'] ?? '') === $s): echo 'selected'; endif; ?>><?php echo e($s); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="w-32">
                <label class="label" for="filter-priority">Prioritas</label>
                <select id="filter-priority" name="priority" class="field appearance-none py-2 pr-8">
                    <option value="">Semua</option>
                    <?php $__currentLoopData = $priorities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($p); ?>" <?php if(($filters['priority'] ?? '') === $p): echo 'selected'; endif; ?>><?php echo e($p); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="w-40">
                <label class="label" for="filter-category">Kategori</label>
                <select id="filter-category" name="category_id" class="field appearance-none py-2 pr-8">
                    <option value="">Semua</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($category->id); ?>" <?php if((string) ($filters['category_id'] ?? '') === (string) $category->id): echo 'selected'; endif; ?>><?php echo e($category->category_name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div>
                <label class="label" for="filter-from">Dari</label>
                <input id="filter-from" type="date" name="date_from" value="<?php echo e($filters['date_from'] ?? ''); ?>" <?php if(($filters['period_mode'] ?? 'custom') !== 'custom'): echo 'disabled'; endif; ?> class="field py-2" />
            </div>

            <div>
                <label class="label" for="filter-to">Sampai</label>
                <input id="filter-to" type="date" name="date_to" value="<?php echo e($filters['date_to'] ?? ''); ?>" <?php if(($filters['period_mode'] ?? 'custom') !== 'custom'): echo 'disabled'; endif; ?> class="field py-2" />
            </div>

            <div>
                <label class="label" for="filter-period-mode">Mode Periode</label>
                <select id="filter-period-mode" name="period_mode" class="field appearance-none py-2 pr-8">
                    <?php $__currentLoopData = ['custom' => 'Custom', 'day' => 'Harian', 'month' => 'Bulanan', 'year' => 'Tahunan']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($value); ?>" <?php if(($filters['period_mode'] ?? 'custom') === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div data-period-input="day" <?php if(($filters['period_mode'] ?? 'custom') !== 'day'): ?> hidden <?php endif; ?>>
                <label class="label" for="filter-period-date">Tanggal</label>
                <input id="filter-period-date" type="date" name="period_date" value="<?php echo e($filters['period_date'] ?? ''); ?>" <?php if(($filters['period_mode'] ?? '') !== 'day'): echo 'disabled'; endif; ?> class="field py-2" />
            </div>

            <div data-period-input="month" <?php if(($filters['period_mode'] ?? 'custom') !== 'month'): ?> hidden <?php endif; ?>>
                <label class="label" for="filter-period-month">Bulan</label>
                <input id="filter-period-month" type="month" name="period_month" value="<?php echo e($filters['period_month'] ?? ''); ?>" <?php if(($filters['period_mode'] ?? '') !== 'month'): echo 'disabled'; endif; ?> class="field py-2" />
            </div>

            <div data-period-input="year" <?php if(($filters['period_mode'] ?? 'custom') !== 'year'): ?> hidden <?php endif; ?>>
                <label class="label" for="filter-period-year">Tahun</label>
                <input id="filter-period-year" type="number" min="2000" max="2100" name="period_year" value="<?php echo e($filters['period_year'] ?? ''); ?>" placeholder="<?php echo e(now()->year); ?>" <?php if(($filters['period_mode'] ?? '') !== 'year'): echo 'disabled'; endif; ?> class="field py-2" />
            </div>

            <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['type' => 'submit','variant' => 'secondary','size' => 'md']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','variant' => 'secondary','size' => 'md']); ?>Filter <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
            <a href="<?php echo e(route('admin.tickets.index')); ?>" class="px-2 py-2 font-mono text-[10px] font-semibold uppercase tracking-wide text-faint hover:text-muted">Reset</a>

            <span class="mx-1 h-5 w-px bg-rule"></span>

            <a href="<?php echo e(route('admin.tickets.export', array_filter(request()->query()))); ?>"
                class="px-2 py-2 font-mono text-[10px] font-semibold uppercase tracking-wide text-stamp-700 underline decoration-stamp-300 underline-offset-4 hover:text-stamp-900">Ekspor CSV &darr;</a>
            <button type="button" onclick="openDialog('ticket-import-dialog')"
                class="px-2 py-2 font-mono text-[10px] font-semibold uppercase tracking-wide text-stamp-700 underline decoration-stamp-300 underline-offset-4 hover:text-stamp-900">Impor CSV &uarr;</button>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[1000px] text-left text-sm">
                <thead>
                    <tr class="border-b border-rule bg-paper/60 font-mono text-[10px] uppercase tracking-[0.14em] text-muted">
                        <th class="px-6 py-2.5 font-semibold">No. Tiket</th>
                        <th class="px-6 py-2.5 font-semibold">Pelapor</th>
                        <th class="px-6 py-2.5 font-semibold">Departemen</th>
                        <th class="px-6 py-2.5 font-semibold">Kategori</th>
                        <th class="px-6 py-2.5 font-semibold">Prioritas</th>
                        <th class="px-6 py-2.5 font-semibold">Status</th>
                        <th class="px-6 py-2.5 font-semibold">Teknisi</th>
                        <th class="px-6 py-2.5 font-semibold">SLA Due</th>
                        <th class="px-6 py-2.5 text-right font-semibold"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-rule/70">
                    <?php $__empty_1 = true; $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="transition hover:bg-paper/70 <?php echo e($ticket->isOverdue() ? 'bg-critical-50/50' : ''); ?>">
                            <td class="whitespace-nowrap px-6 py-3.5">
                                <a href="<?php echo e(route('admin.tickets.show', $ticket)); ?>" class="font-mono text-[13px] font-semibold text-stamp-800 underline decoration-stamp-200 underline-offset-4 hover:text-stamp-600"><?php echo e($ticket->ticket_number); ?></a>
                                <span class="block font-mono text-[10px] uppercase tracking-wide text-faint"><?php echo e($ticket->created_at->format('d M Y, H.i')); ?></span>
                            </td>
                            <td class="max-w-[160px] truncate px-6 py-3.5 font-medium text-ink"><?php echo e($ticket->requester_name); ?></td>
                            <td class="px-6 py-3.5 text-muted"><?php echo e($ticket->department ?? '—'); ?></td>
                            <td class="px-6 py-3.5 text-muted"><?php echo e($ticket->category?->category_name); ?></td>
                            <td class="px-6 py-3.5"><?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
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
<?php endif; ?></td>
                            <td class="px-6 py-3.5"><?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
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
<?php endif; ?></td>
                            <td class="whitespace-nowrap px-6 py-3.5 text-muted">
                                <?php echo e($ticket->assignedTechnician?->technician_name ?? '—'); ?>

                            </td>
                            <td class="whitespace-nowrap px-6 py-3.5 font-mono text-xs tabular-nums">
                                <?php if($ticket->isOverdue()): ?>
                                    <span class="font-semibold text-critical-600"><?php echo e($ticket->sla_due_at->format('d M H:i')); ?> — lewat</span>
                                <?php elseif($ticket->isActive()): ?>
                                    <span class="text-muted"><?php echo e($ticket->sla_due_at->format('d M H:i')); ?></span>
                                <?php else: ?>
                                    <span class="text-faint"><?php echo e($ticket->sla_due_at?->format('d M H:i')); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="whitespace-nowrap px-6 py-3.5 text-right">
                                <a href="<?php echo e(route('admin.tickets.show', $ticket)); ?>" class="font-mono text-[10px] font-semibold uppercase tracking-wide text-stamp-700 underline decoration-stamp-300 underline-offset-4 hover:text-stamp-800">Detail</a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="9" class="px-6 py-10"><?php if (isset($component)) { $__componentOriginal074a021b9d42f490272b5eefda63257c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal074a021b9d42f490272b5eefda63257c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.empty-state','data' => ['description' => 'Tidak ada tiket yang cocok dengan filter.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['description' => 'Tidak ada tiket yang cocok dengan filter.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal074a021b9d42f490272b5eefda63257c)): ?>
<?php $attributes = $__attributesOriginal074a021b9d42f490272b5eefda63257c; ?>
<?php unset($__attributesOriginal074a021b9d42f490272b5eefda63257c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal074a021b9d42f490272b5eefda63257c)): ?>
<?php $component = $__componentOriginal074a021b9d42f490272b5eefda63257c; ?>
<?php unset($__componentOriginal074a021b9d42f490272b5eefda63257c); ?>
<?php endif; ?></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="border-t border-dashed border-rule px-6 py-3">
            <?php echo e($tickets->links()); ?>

        </div>
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

    <?php if (isset($component)) { $__componentOriginal227a88e4577f56072361db363cadef5a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal227a88e4577f56072361db363cadef5a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal-dialog','data' => ['id' => 'ticket-import-dialog','title' => 'Impor Tiket (CSV)']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal-dialog'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'ticket-import-dialog','title' => 'Impor Tiket (CSV)']); ?>
        <form method="POST" action="<?php echo e(route('admin.tickets.import')); ?>" enctype="multipart/form-data" data-disable-on-submit class="space-y-4">
            <?php echo csrf_field(); ?>

            <p class="text-xs leading-relaxed text-muted">
                Dua format didukung: <strong class="text-ink">hasil Ekspor CSV</strong> aplikasi ini, atau
                <strong class="text-ink">CSV Helpdesk lama</strong> (kolom TicketID, Tanggal dd/mm/yyyy, NamaLengkap, Kategori, Kendala, Priority, Status, Teknisi).
                Baris dengan No. Tiket yang sudah ada <strong class="text-ink">dilewati</strong>;
                format legacy mempertahankan status &amp; tanggal, SLA Due dihitung otomatis dari tanggal tiket dibuat,
                dan nama teknisi dinormalisasi ke master Teknisi.
            </p>

            <input type="file" name="file" accept=".csv,text/csv" required
                class="field w-full py-2 file:mr-3 file:rounded-[4px] file:border-0 file:bg-stamp-50 file:px-3 file:py-1.5 file:font-mono file:text-[10px] file:font-semibold file:uppercase file:tracking-wide file:text-stamp-800" />

            <?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="font-mono text-[11px] uppercase tracking-wide text-critical-600"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <div class="flex justify-end gap-2 pt-1">
                <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['variant' => 'secondary','size' => 'sm','type' => 'button','onclick' => 'closeDialog(\'ticket-import-dialog\')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'secondary','size' => 'sm','type' => 'button','onclick' => 'closeDialog(\'ticket-import-dialog\')']); ?>Batal <?php echo $__env->renderComponent(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['size' => 'sm','type' => 'submit']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'sm','type' => 'submit']); ?>Impor Sekarang <?php echo $__env->renderComponent(); ?>
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
        </form>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal227a88e4577f56072361db363cadef5a)): ?>
<?php $attributes = $__attributesOriginal227a88e4577f56072361db363cadef5a; ?>
<?php unset($__attributesOriginal227a88e4577f56072361db363cadef5a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal227a88e4577f56072361db363cadef5a)): ?>
<?php $component = $__componentOriginal227a88e4577f56072361db363cadef5a; ?>
<?php unset($__componentOriginal227a88e4577f56072361db363cadef5a); ?>
<?php endif; ?>

    <script>
        const periodMode = document.getElementById('filter-period-mode');

        function syncPeriodInputs() {
            document.querySelectorAll('[data-period-input]').forEach((el) => {
                const active = el.dataset.periodInput === periodMode.value;
                el.hidden = !active;
                const field = el.querySelector('input');
                if (field) {
                    field.disabled = !active;
                }
            });

            const custom = periodMode.value === 'custom';
            ['filter-from', 'filter-to'].forEach((id) => {
                const input = document.getElementById(id);
                if (input) {
                    input.disabled = !custom;
                }
            });
        }

        periodMode.addEventListener('change', syncPeriodInputs);
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\99. Other\PROJECT\project-app\ticket-it-app\resources\views/admin/tickets/index.blade.php ENDPATH**/ ?>