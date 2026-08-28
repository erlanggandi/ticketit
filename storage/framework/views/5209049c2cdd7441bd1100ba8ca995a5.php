<?php $__env->startSection('title', 'Karyawan'); ?>
<?php $__env->startSection('page_title', 'Master Data — Karyawan'); ?>
<?php $__env->startSection('page_subtitle', 'Data untuk autocomplete pada form tiket publik'); ?>

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
        <div class="flex items-center justify-between border-b border-dashed border-rule bg-paper/50 px-6 py-3">
            <p class="font-mono text-[10px] uppercase tracking-[0.14em] text-muted">Total <strong class="text-ink"><?php echo e($employees->total()); ?></strong> karyawan</p>
            <div class="flex items-center gap-3">
                <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['size' => 'sm','onclick' => 'openCreateDialog()']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'sm','onclick' => 'openCreateDialog()']); ?>+ Tambah Karyawan <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
                <span class="h-5 w-px bg-rule"></span>
                <a href="<?php echo e(route('admin.employees.export')); ?>"
                    class="font-mono text-[10px] font-semibold uppercase tracking-wide text-stamp-700 underline decoration-stamp-300 underline-offset-4 hover:text-stamp-900">Ekspor CSV &darr;</a>
                <button type="button" onclick="openDialog('employee-import-dialog')"
                    class="font-mono text-[10px] font-semibold uppercase tracking-wide text-stamp-700 underline decoration-stamp-300 underline-offset-4 hover:text-stamp-900">Impor CSV &uarr;</button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[640px] text-left text-sm">
                <thead>
                    <tr class="border-b border-rule bg-paper/60 font-mono text-[10px] uppercase tracking-[0.14em] text-muted">
                        <th class="px-6 py-2.5 font-semibold">Nama</th>
                        <th class="px-6 py-2.5 font-semibold">Departemen</th>
                        <th class="px-6 py-2.5 font-semibold">Kontak</th>
                        <th class="px-6 py-2.5 font-semibold">Status</th>
                        <th class="px-6 py-2.5 text-right font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-rule/70">
                    <?php $__empty_1 = true; $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="transition hover:bg-paper/70">
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-[3px] border border-stamp-600/40 bg-stamp-50 font-mono text-[11px] font-semibold text-stamp-800">
                                        <?php echo e(collect(explode(' ', $employee->employee_name))->map(fn ($w) => mb_substr($w, 0, 1))->slice(0, 2)->implode('')); ?>

                                    </span>
                                    <span class="font-medium text-ink"><?php echo e($employee->employee_name); ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-3.5 text-muted"><?php echo e($employee->department ?? '—'); ?></td>
                            <td class="px-6 py-3.5 text-muted"><?php echo e($employee->contact ?? '—'); ?></td>
                            <td class="px-6 py-3.5">
                                <span class="chip <?php echo e($employee->is_active ? 'border-ok-500/60 bg-ok-50 text-ok-700' : 'border-ink/25 bg-ink/[0.06] text-muted'); ?>">
                                    <?php echo e($employee->is_active ? 'Aktif' : 'Nonaktif'); ?>

                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-3.5 text-right">
                                <button type="button" class="font-mono text-[10px] font-semibold uppercase tracking-wide text-stamp-700 underline decoration-stamp-300 underline-offset-4 hover:text-stamp-800"
                                        data-payload="<?php echo e(json_encode(['id' => $employee->id, 'employee_name' => $employee->employee_name, 'department' => $employee->department, 'contact' => $employee->contact])); ?>"
                                        onclick="openEditDialog(this)">Edit</button>
                                <span class="mx-2 text-rule">|</span>
                                <form method="POST" action="<?php echo e(route('admin.employees.toggle', $employee)); ?>" class="inline" onsubmit="return confirm('Ubah status karyawan ini?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <button type="submit" class="font-mono text-[10px] font-semibold uppercase tracking-wide text-signal-600 hover:text-signal-700"><?php echo e($employee->is_active ? 'Nonaktifkan' : 'Aktifkan'); ?></button>
                                </form>
                                <?php if($employee->tickets()->count() === 0): ?>
                                    <span class="mx-2 text-rule">|</span>
                                    <form method="POST" action="<?php echo e(route('admin.employees.destroy', $employee)); ?>" class="inline" onsubmit="return confirm('Hapus data <?php echo e($employee->employee_name); ?>?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="font-mono text-[10px] font-semibold uppercase tracking-wide text-critical-600 hover:text-critical-700">Hapus</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="5" class="px-6 py-10"><?php if (isset($component)) { $__componentOriginal074a021b9d42f490272b5eefda63257c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal074a021b9d42f490272b5eefda63257c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.empty-state','data' => ['description' => 'Belum ada data karyawan.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['description' => 'Belum ada data karyawan.']); ?>
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
            <?php echo e($employees->links()); ?>

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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal-dialog','data' => ['id' => 'employee-dialog','title' => 'Data Karyawan']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal-dialog'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'employee-dialog','title' => 'Data Karyawan']); ?>
        <form method="POST" id="employee-form" action="<?php echo e(route('admin.employees.store')); ?>" data-disable-on-submit class="space-y-4">
            <?php echo csrf_field(); ?>

            <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['name' => 'employee_name','label' => 'Nama Lengkap','required' => true,'placeholder' => 'mis. Budi Santoso','maxlength' => '150']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'employee_name','label' => 'Nama Lengkap','required' => true,'placeholder' => 'mis. Budi Santoso','maxlength' => '150']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $attributes = $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $component = $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>

            <div class="grid gap-4 sm:grid-cols-2">
                <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['name' => 'department','label' => 'Departemen','placeholder' => 'mis. Finance','maxlength' => '100']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'department','label' => 'Departemen','placeholder' => 'mis. Finance','maxlength' => '100']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $attributes = $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $component = $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['name' => 'contact','label' => 'Kontak (email/telp)','placeholder' => 'opsional','maxlength' => '150']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'contact','label' => 'Kontak (email/telp)','placeholder' => 'opsional','maxlength' => '150']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $attributes = $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $component = $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
            </div>

            <label class="flex items-center gap-2.5 text-sm text-muted">
                <input type="checkbox" name="is_active" value="1" checked class="h-4 w-4 rounded-[3px] border-ink/40 accent-stamp-600" />
                Aktif (ikut autocomplete form publik)
            </label>

            <div class="flex justify-end gap-2 pt-1">
                <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['variant' => 'secondary','size' => 'sm','type' => 'button','onclick' => 'closeDialog(\'employee-dialog\')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'secondary','size' => 'sm','type' => 'button','onclick' => 'closeDialog(\'employee-dialog\')']); ?>Batal <?php echo $__env->renderComponent(); ?>
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
<?php $component->withAttributes(['size' => 'sm','type' => 'submit']); ?>Simpan <?php echo $__env->renderComponent(); ?>
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

    <?php if (isset($component)) { $__componentOriginal227a88e4577f56072361db363cadef5a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal227a88e4577f56072361db363cadef5a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal-dialog','data' => ['id' => 'employee-import-dialog','title' => 'Impor Karyawan (CSV)']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal-dialog'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'employee-import-dialog','title' => 'Impor Karyawan (CSV)']); ?>
        <form method="POST" action="<?php echo e(route('admin.employees.import')); ?>" enctype="multipart/form-data" data-disable-on-submit class="space-y-4">
            <?php echo csrf_field(); ?>

            <p class="text-xs leading-relaxed text-muted">
                Kolom: <strong class="text-ink">employee_name, department, contact, is_active</strong>.
                Baris dengan nama yang sudah ada akan <strong class="text-ink">diperbarui</strong> dari isi file;
                kolom kosong pada departemen/kontak akan dikosongkan, sedangkan is_active kosong mempertahankan status lama
                (baru = Aktif). Impor tidak pernah menghapus data.
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['variant' => 'secondary','size' => 'sm','type' => 'button','onclick' => 'closeDialog(\'employee-import-dialog\')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'secondary','size' => 'sm','type' => 'button','onclick' => 'closeDialog(\'employee-import-dialog\')']); ?>Batal <?php echo $__env->renderComponent(); ?>
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
        const storeUrl = <?php echo json_encode(route('admin.employees.store'), 15, 512) ?>;
        const updateUrlTemplate = <?php echo json_encode(url('/admin/employees/__ID__'), 15, 512) ?>;

        function removeMethodField(form) {
            form.querySelector('input[name="_method"]')?.remove();
        }

        function openCreateDialog() {
            const form = document.getElementById('employee-form');
            form.reset();
            form.querySelector('[name="is_active"]').checked = true;
            removeMethodField(form);
            form.action = storeUrl;
            openDialog('employee-dialog');
        }

        function openEditDialog(buttonEl) {
            const data = JSON.parse(buttonEl.dataset.payload);
            const form = document.getElementById('employee-form');
            form.reset();

            let methodField = form.querySelector('input[name="_method"]');
            if (!methodField) {
                methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'PATCH';
                form.prepend(methodField);
            }

            form.action = updateUrlTemplate.replace('__ID__', data.id);
            form.querySelector('[name="employee_name"]').value = data.employee_name;
            form.querySelector('[name="department"]').value = data.department ?? '';
            form.querySelector('[name="contact"]').value = data.contact ?? '';
            form.querySelector('[name="is_active"]').checked = false;

            openDialog('employee-dialog');
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\99. Other\PROJECT\project-app\ticket-it-app\resources\views/admin/employees/index.blade.php ENDPATH**/ ?>