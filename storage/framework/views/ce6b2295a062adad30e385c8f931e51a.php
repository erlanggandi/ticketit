<?php $__env->startSection('title', 'Pengaturan Website'); ?>
<?php $__env->startSection('page_title', 'Pengaturan Website'); ?>
<?php $__env->startSection('page_subtitle', 'Identitas & tampilan halaman publik'); ?>

<?php $__env->startSection('content'); ?>
    <form method="POST" action="<?php echo e(route('admin.site-settings.update')); ?>" enctype="multipart/form-data" data-disable-on-submit class="space-y-6 max-w-4xl">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Card 1: Identitas Website -->
            <?php if (isset($component)) { $__componentOriginal53747ceb358d30c0105769f8471417f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53747ceb358d30c0105769f8471417f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card','data' => ['title' => 'Identitas Website']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Identitas Website']); ?>
                <div class="space-y-5">
                    <div>
                        <label for="app_name" class="label">Nama Aplikasi <span class="text-critical-500" aria-hidden="true">*</span></label>
                        <input id="app_name" type="text" name="app_name" value="<?php echo e(old('app_name', $setting->app_name)); ?>" class="field <?php $__errorArgs = ['app_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> field-error <?php endif; ?>" required maxlength="100" />
                        <?php $__errorArgs = ['app_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1.5 font-mono text-[11px] uppercase tracking-wide text-critical-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="app_short_name" class="label">Nama Singkat (Logo) <span class="text-critical-500" aria-hidden="true">*</span></label>
                        <input id="app_short_name" type="text" name="app_short_name" value="<?php echo e(old('app_short_name', $setting->app_short_name)); ?>" class="field <?php $__errorArgs = ['app_short_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> field-error <?php endif; ?>" required maxlength="20" />
                        <p class="mt-1.5 text-xs leading-relaxed text-faint">Ditampilkan sebagai inisial logo di header (mis. "IT").</p>
                        <?php $__errorArgs = ['app_short_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1.5 font-mono text-[11px] uppercase tracking-wide text-critical-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="tagline" class="label">Tagline <span class="text-critical-500" aria-hidden="true">*</span></label>
                        <input id="tagline" type="text" name="tagline" value="<?php echo e(old('tagline', $setting->tagline)); ?>" class="field <?php $__errorArgs = ['tagline'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> field-error <?php endif; ?>" required maxlength="150" />
                        <p class="mt-1.5 text-xs leading-relaxed text-faint">Sub-judul di bawah nama aplikasi pada header.</p>
                        <?php $__errorArgs = ['tagline'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1.5 font-mono text-[11px] uppercase tracking-wide text-critical-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="footer_text" class="label">Teks Footer <span class="text-critical-500" aria-hidden="true">*</span></label>
                        <input id="footer_text" type="text" name="footer_text" value="<?php echo e(old('footer_text', $setting->footer_text)); ?>" class="field <?php $__errorArgs = ['footer_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> field-error <?php endif; ?>" required maxlength="200" />
                        <p class="mt-1.5 text-xs leading-relaxed text-faint">Tahun otomatis ditambahkan di akhir.</p>
                        <?php $__errorArgs = ['footer_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1.5 font-mono text-[11px] uppercase tracking-wide text-critical-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
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

            <!-- Card 2: Tampilan Form -->
            <?php if (isset($component)) { $__componentOriginal53747ceb358d30c0105769f8471417f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53747ceb358d30c0105769f8471417f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card','data' => ['title' => 'Tampilan Form Tiket']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Tampilan Form Tiket']); ?>
                <div class="space-y-5">
                    <div>
                        <label for="form_title" class="label">Judul Form <span class="text-critical-500" aria-hidden="true">*</span></label>
                        <input id="form_title" type="text" name="form_title" value="<?php echo e(old('form_title', $setting->form_title)); ?>" class="field <?php $__errorArgs = ['form_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> field-error <?php endif; ?>" required maxlength="200" />
                        <?php $__errorArgs = ['form_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1.5 font-mono text-[11px] uppercase tracking-wide text-critical-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="form_description" class="label">Deskripsi Form</label>
                        <textarea id="form_description" name="form_description" rows="4" class="field <?php $__errorArgs = ['form_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> field-error <?php endif; ?>" maxlength="1000"><?php echo e(old('form_description', $setting->form_description)); ?></textarea>
                        <p class="mt-1.5 text-xs leading-relaxed text-faint">Penjelasan singkat di bawah judul form.</p>
                        <?php $__errorArgs = ['form_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1.5 font-mono text-[11px] uppercase tracking-wide text-critical-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
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
        </div>

        <!-- Card 3: Logo & Favicon -->
        <?php if (isset($component)) { $__componentOriginal53747ceb358d30c0105769f8471417f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53747ceb358d30c0105769f8471417f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card','data' => ['title' => 'Logo & Favicon']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Logo & Favicon']); ?>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label for="logo" class="label">Logo (PNG / SVG, maks 512 KB)</label>
                    <input id="logo" type="file" name="logo" accept="image/png,image/x-icon,image/svg+xml" class="field <?php $__errorArgs = ['logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> field-error <?php endif; ?>" />
                    <?php $__errorArgs = ['logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1.5 font-mono text-[11px] uppercase tracking-wide text-critical-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    <?php if($setting->logo_path): ?>
                        <div class="mt-3 flex items-center gap-3 rounded-[5px] border border-dashed border-rule bg-paper/60 p-3">
                            <img src="<?php echo e(asset('storage/'.$setting->logo_path)); ?>" alt="Logo saat ini" class="h-12 w-12 rounded-[3px] border border-rule object-contain bg-sheet" />
                            <div class="min-w-0 flex-1">
                                <p class="font-mono text-[10px] uppercase tracking-wide text-muted">Logo saat ini</p>
                                <p class="truncate font-mono text-[11px] text-ink"><?php echo e($setting->logo_path); ?></p>
                            </div>
                            <label class="flex items-center gap-1.5 text-xs text-critical-600">
                                <input type="checkbox" name="remove_logo" value="1" /> Hapus
                            </label>
                        </div>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="favicon" class="label">Favicon (PNG / ICO / SVG, maks 256 KB)</label>
                    <input id="favicon" type="file" name="favicon" accept="image/png,image/x-icon,image/svg+xml" class="field <?php $__errorArgs = ['favicon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> field-error <?php endif; ?>" />
                    <?php $__errorArgs = ['favicon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1.5 font-mono text-[11px] uppercase tracking-wide text-critical-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    <?php if($setting->favicon_path): ?>
                        <div class="mt-3 flex items-center gap-3 rounded-[5px] border border-dashed border-rule bg-paper/60 p-3">
                            <img src="<?php echo e(asset('storage/'.$setting->favicon_path)); ?>" alt="Favicon saat ini" class="h-8 w-8 rounded-[3px] border border-rule object-contain bg-sheet" />
                            <div class="min-w-0 flex-1">
                                <p class="font-mono text-[10px] uppercase tracking-wide text-muted">Favicon saat ini</p>
                                <p class="truncate font-mono text-[11px] text-ink"><?php echo e($setting->favicon_path); ?></p>
                            </div>
                            <label class="flex items-center gap-1.5 text-xs text-critical-600">
                                <input type="checkbox" name="remove_favicon" value="1" /> Hapus
                            </label>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <p class="mt-4 border-t border-dashed border-rule pt-3 font-mono text-[10px] uppercase tracking-wide text-faint">
                File disimpan di storage publik. Jalankan <code class="bg-sheet px-1 py-0.5">php artisan storage:link</code> jika belum.
            </p>
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

        <div class="flex justify-end">
            <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['type' => 'submit']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit']); ?>Simpan Pengaturan <?php echo $__env->renderComponent(); ?>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\99. Other\PROJECT\project-app\ticket-it-app\resources\views/admin/site-settings/edit.blade.php ENDPATH**/ ?>