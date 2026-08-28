<?php $__env->startSection('title', 'Buat Tiket'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
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
            <div class="kop">
                <span>No. GSI4-IT-001E</span>
                <span class="hidden sm:inline"><?php echo e(now()->translatedFormat('d M Y')); ?></span>
                <span>Lembar 1/1</span>
            </div>

            <div class="p-5 sm:p-7">
                <?php
                    $ss = $siteSetting ?? (object) [
                        'form_title' => 'Lapor Masalah IT',
                        'form_description' => 'Isi formulir ini untuk mencatat kendala yang Anda alami. Tim IT menangani sesuai tingkat urgensi.',
                    ];
                ?>
                <h1 class="display text-2xl sm:text-3xl"><?php echo e($ss->form_title); ?></h1>
                <?php if($ss->form_description): ?>
                    <p class="mt-2 max-w-md text-sm leading-relaxed text-muted"><?php echo e($ss->form_description); ?></p>
                <?php endif; ?>
                <?php if($ss->form_description): ?>
                    <p class="mt-2 max-w-md text-sm leading-relaxed text-muted"><?php echo e($ss->form_description); ?></p>
                <?php endif; ?>

                <form method="POST" action="<?php echo e(route('tickets.store')); ?>" data-disable-on-submit class="mt-7 space-y-6" autocomplete="off">
                    <?php echo csrf_field(); ?>

                    <div class="relative" id="autocomplete-wrap">
                        <label for="requester_name" class="label">
                            <span class="mr-2 text-faint">01 .</span>Nama Pelapor <span class="text-critical-500" aria-hidden="true">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" name="requester_name" id="requester_name"
                                value="<?php echo e(old('requester_name')); ?>"
                                required minlength="2" maxlength="150"
                                placeholder="Ketik nama Anda…"
                                class="field <?php echo e($errors->has('requester_name') ? 'field-error' : ''); ?>" />
                        </div>
                        <input type="hidden" name="employee_id" id="employee_id" value="<?php echo e(old('employee_id')); ?>">

                        <div id="autocomplete-list" class="absolute z-10 hidden w-full overflow-hidden rounded-[5px] border border-ink bg-sheet shadow-[var(--shadow-pop)]"></div>

                        <p class="mt-1.5 text-xs leading-relaxed text-faint">Jika nama Anda terdaftar, saran akan muncul otomatis. Tidak terdaftar? Lanjutkan saja secara manual.</p>

                        <?php $__errorArgs = ['requester_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1.5 font-mono text-[11px] uppercase tracking-wide text-critical-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        <?php if (isset($component)) { $__componentOriginaled2cde6083938c436304f332ba96bb7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaled2cde6083938c436304f332ba96bb7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.select','data' => ['name' => 'category_id','label' => '02 · Kategori Masalah','required' => true,'placeholder' => 'Pilih kategori…','selected' => old('category_id'),'options' => $categories->pluck('category_name', 'id')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'category_id','label' => '02 · Kategori Masalah','required' => true,'placeholder' => 'Pilih kategori…','selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('category_id')),'options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($categories->pluck('category_name', 'id'))]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.select','data' => ['name' => 'priority','label' => '03 · Tingkat Urgensi','required' => true,'placeholder' => 'Pilih urgensi…','selected' => old('priority'),'hint' => 'Menentukan target waktu penanganan (SLA).','options' => ['Low' => 'Low — santai', 'Medium' => 'Medium — normal', 'High' => 'High — mengganggu kerja', 'Critical' => 'Critical — operasional berhenti']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'priority','label' => '03 · Tingkat Urgensi','required' => true,'placeholder' => 'Pilih urgensi…','selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('priority')),'hint' => 'Menentukan target waktu penanganan (SLA).','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['Low' => 'Low — santai', 'Medium' => 'Medium — normal', 'High' => 'High — mengganggu kerja', 'Critical' => 'Critical — operasional berhenti'])]); ?>
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
                    </div>

                    <div>
                        <?php if (isset($component)) { $__componentOriginal4727f9fd7c3055c2cf9c658d89b16886 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4727f9fd7c3055c2cf9c658d89b16886 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.textarea','data' => ['name' => 'description','label' => '04 · Deskripsi Masalah','required' => true,'rows' => '5','placeholder' => 'Jelaskan masalah yang dialami: apa yang terjadi, sejak kapan, dan pesan error jika ada…']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('textarea'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'description','label' => '04 · Deskripsi Masalah','required' => true,'rows' => '5','placeholder' => 'Jelaskan masalah yang dialami: apa yang terjadi, sejak kapan, dan pesan error jika ada…']); ?><?php echo e(old('description')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4727f9fd7c3055c2cf9c658d89b16886)): ?>
<?php $attributes = $__attributesOriginal4727f9fd7c3055c2cf9c658d89b16886; ?>
<?php unset($__attributesOriginal4727f9fd7c3055c2cf9c658d89b16886); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4727f9fd7c3055c2cf9c658d89b16886)): ?>
<?php $component = $__componentOriginal4727f9fd7c3055c2cf9c658d89b16886; ?>
<?php unset($__componentOriginal4727f9fd7c3055c2cf9c658d89b16886); ?>
<?php endif; ?>
                    </div>

                    <div class="flex flex-col gap-3 border-t border-dashed border-rule pt-6 sm:flex-row sm:items-center sm:justify-between">
                        <p class="font-mono text-[10px] uppercase tracking-[0.16em] text-faint">Semua field bertanda * wajib diisi</p>
                        <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['type' => 'submit','size' => 'lg']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','size' => 'lg']); ?>
                            Kirim Laporan
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
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
                </form>
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

        <p class="text-center font-mono text-[11px] uppercase tracking-[0.14em] text-faint">
            Sudah punya tiket?
            <a href="<?php echo e(route('tickets.check')); ?>" class="font-semibold text-stamp-700 underline decoration-stamp-300 underline-offset-4 hover:text-stamp-800">Cek status di sini</a>
        </p>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        (function () {
            const input = document.getElementById('requester_name');
            const hiddenId = document.getElementById('employee_id');
            const list = document.getElementById('autocomplete-list');
            const wrap = document.getElementById('autocomplete-wrap');
            let controller = null;
            let debounceTimer = null;

            function hideList() {
                list.classList.add('hidden');
                list.innerHTML = '';
            }

            input.addEventListener('blur', () => setTimeout(hideList, 180));

            input.addEventListener('input', () => {
                hiddenId.value = '';
                clearTimeout(debounceTimer);
                const q = input.value.trim();

                if (q.length < 2) {
                    hideList();
                    return;
                }

                debounceTimer = setTimeout(async () => {
                    if (controller) controller.abort();
                    controller = new AbortController();

                    try {
                        const res = await fetch("<?php echo e(route('api.employees.search')); ?>?q=" + encodeURIComponent(q), {
                            headers: { Accept: 'application/json' },
                            signal: controller.signal,
                        });

                        if (!res.ok) return;
                        const json = await res.json();

                        if (!json.data.length) {
                            hideList();
                            return;
                        }

                        list.innerHTML = '';
                        json.data.forEach((emp) => {
                            const btn = document.createElement('button');
                            btn.type = 'button';
                            btn.className = 'flex w-full items-center gap-3 px-4 py-2.5 text-left transition hover:bg-paper';
                            btn.innerHTML =
                                '<span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-[3px] border border-stamp-600/50 bg-stamp-50 font-mono text-[11px] font-semibold text-stamp-800">' +
                                    emp.name.split(' ').map(w => w[0]).slice(0, 2).join('').toUpperCase() +
                                '</span>' +
                                '<span><span class="block text-sm font-semibold text-ink">' + emp.name + '</span>' +
                                '<span class="block text-xs text-muted">' + (emp.department ?? '-') + '</span></span>';

                            btn.addEventListener('mousedown', (e) => {
                                e.preventDefault();
                                input.value = emp.name;
                                hiddenId.value = emp.id;
                                hideList();
                            });

                            list.appendChild(btn);
                        });

                        list.classList.remove('hidden');
                    } catch (e) {}
                }, 250);
            });

            document.addEventListener('click', (e) => {
                if (!wrap.contains(e.target)) hideList();
            });
        })();
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\99. Other\PROJECT\project-app\ticket-it-app\resources\views/public/tickets/create.blade.php ENDPATH**/ ?>