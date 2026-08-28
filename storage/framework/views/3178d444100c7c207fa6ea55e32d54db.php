<?php $__env->startSection('title', 'Alert Telegram'); ?>
<?php $__env->startSection('page_title', 'Pengaturan Alert Telegram'); ?>
<?php $__env->startSection('page_subtitle', 'Notifikasi tiket baru & alert SLA via Bot Telegram'); ?>

<?php $__env->startSection('content'); ?>
    <div class="grid max-w-5xl grid-cols-1 gap-6 xl:grid-cols-3">
        <div class="space-y-6 xl:col-span-2">
            <?php if (isset($component)) { $__componentOriginal53747ceb358d30c0105769f8471417f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53747ceb358d30c0105769f8471417f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card','data' => ['title' => 'Konfigurasi Bot']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Konfigurasi Bot']); ?>
                <form method="POST" action="<?php echo e(route('admin.telegram-settings.update')); ?>" data-disable-on-submit class="space-y-5">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['name' => 'bot_token','type' => 'password','label' => 'Bot Token','required' => !$setting?->bot_token,'placeholder' => ''.e($setting?->bot_token ? 'Biarkan kosong jika tidak ingin mengubah' : '1234567890:AAxxxx...').'','hint' => ''.e($setting?->bot_token ? 'Token tersimpan: '.$setting->maskedBotToken() : 'Dibuat via @BotFather di Telegram.').'','autocomplete' => 'new-password']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'bot_token','type' => 'password','label' => 'Bot Token','required' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(!$setting?->bot_token),'placeholder' => ''.e($setting?->bot_token ? 'Biarkan kosong jika tidak ingin mengubah' : '1234567890:AAxxxx...').'','hint' => ''.e($setting?->bot_token ? 'Token tersimpan: '.$setting->maskedBotToken() : 'Dibuat via @BotFather di Telegram.').'','autocomplete' => 'new-password']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['name' => 'chat_id','label' => 'Chat / Group ID','required' => true,'value' => $setting?->chat_id,'placeholder' => 'mis. -1001234567890','hint' => 'ID chat tujuan notifikasi (bisa grup IT).']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chat_id','label' => 'Chat / Group ID','required' => true,'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($setting?->chat_id),'placeholder' => 'mis. -1001234567890','hint' => 'ID chat tujuan notifikasi (bisa grup IT).']); ?>
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

                    <div class="rounded-[5px] border border-dashed border-rule p-4">
                        <div class="mb-1.5 flex flex-wrap items-center justify-between gap-2">
                            <label for="message_template_new_ticket" class="font-mono text-[11px] font-semibold uppercase tracking-[0.12em] text-ink">Template Pesan — Tiket Baru</label>
                            <button type="button" onclick="insertVars('message_template_new_ticket')" class="font-mono text-[10px] font-semibold uppercase tracking-wide text-stamp-700 hover:underline">Sisipkan variabel</button>
                        </div>
                        <textarea name="message_template_new_ticket" id="message_template_new_ticket" rows="7"
                                  class="field font-mono text-xs leading-relaxed"><?php echo e(old('message_template_new_ticket', $setting?->message_template_new_ticket ?? $defaultTemplateNewTicket)); ?></textarea>
                        <?php $__errorArgs = ['message_template_new_ticket'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 font-mono text-[11px] uppercase tracking-wide text-critical-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="rounded-[5px] border border-dashed border-rule p-4">
                        <div class="mb-1.5 flex flex-wrap items-center justify-between gap-2">
                            <label for="message_template_sla_alert" class="font-mono text-[11px] font-semibold uppercase tracking-[0.12em] text-ink">Template Pesan — Alert SLA</label>
                            <button type="button" onclick="insertVars('message_template_sla_alert')" class="font-mono text-[10px] font-semibold uppercase tracking-wide text-stamp-700 hover:underline">Sisipkan variabel</button>
                        </div>
                        <textarea name="message_template_sla_alert" id="message_template_sla_alert" rows="7"
                                  class="field font-mono text-xs leading-relaxed"><?php echo e(old('message_template_sla_alert', $setting?->message_template_sla_alert ?? $defaultTemplateSlaAlert)); ?></textarea>
                        <?php $__errorArgs = ['message_template_sla_alert'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 font-mono text-[11px] uppercase tracking-wide text-critical-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="rounded-[4px] border border-stamp-200 bg-stamp-50 p-3.5 text-xs leading-relaxed text-stamp-900">
                        Variabel yang didukung:
                        <?php $__currentLoopData = $variables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variable): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <code class="mx-0.5 rounded-[3px] bg-sheet px-1.5 py-0.5 font-mono text-[11px] text-stamp-700"><?php echo e($variable); ?></code>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

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
                </form>
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

        <div class="space-y-6">
            <?php if (isset($component)) { $__componentOriginal53747ceb358d30c0105769f8471417f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53747ceb358d30c0105769f8471417f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card','data' => ['title' => 'Test Kirim Pesan']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Test Kirim Pesan']); ?>
                <p class="mb-4 text-sm leading-relaxed text-muted">
                    Kirim pesan percobaan ke Chat ID yang tersimpan untuk memastikan konfigurasi sudah benar.
                </p>
                <button type="button" id="test-send-btn"
                        class="btn btn-secondary w-full">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                    </svg>
                    Test Kirim Pesan
                </button>

                <div id="test-result" class="mt-4 hidden rounded-[5px] border-l-4 px-3 py-2.5 text-sm"></div>

                <?php if(!$setting?->bot_token): ?>
                    <p class="mt-3 font-mono text-[11px] uppercase tracking-wide text-signal-600">Simpan Bot Token terlebih dahulu sebelum melakukan test.</p>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card','data' => ['title' => 'Cara Setup']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Cara Setup']); ?>
                <ol class="list-decimal space-y-2 pl-4 text-sm leading-relaxed text-muted marker:font-mono marker:text-faint">
                    <li>Buka <strong class="text-ink">@BotFather</strong> di Telegram, buat bot dengan <code class="rounded-[3px] bg-paper px-1 py-0.5 font-mono text-xs">/newbot</code>, salin token yang diberikan.</li>
                    <li>Tambahkan bot ke grup IT (atau chat langsung), kirim satu pesan apa pun.</li>
                    <li>Ambil Chat ID grup via <code class="rounded-[3px] bg-paper px-1 py-0.5 font-mono text-xs">@userinfobot</code> atau API getUpdates.</li>
                    <li>Tempel Bot Token &amp; Chat ID pada formulir, klik Simpan.</li>
                    <li>Klik <strong class="text-ink">Test Kirim Pesan</strong> untuk verifikasi.</li>
                </ol>
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
    </div>

    <?php ($lastUpdate = $setting?->updater); ?>

    <?php if($lastUpdate): ?>
        <p class="mt-4 max-w-5xl font-mono text-[10px] uppercase tracking-wider text-faint">Terakhir diperbarui oleh <?php echo e($lastUpdate->name); ?> pada <?php echo e($setting->updated_at->format('d M Y, H:i')); ?>.</p>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        const variableList = <?php echo json_encode($variables, 15, 512) ?>;

        function insertVars(textareaId) {
            const textarea = document.getElementById(textareaId);
            textarea.value += (textarea.value.endsWith('\n') || !textarea.value ? '' : '\n') + '{ticket_number}';
            textarea.focus();
        }

        document.getElementById('test-send-btn')?.addEventListener('click', async function () {
            const btn = this;
            const result = document.getElementById('test-result');
            const originalContent = btn.innerHTML;
            btn.disabled = true;
            btn.textContent = 'Mengirim…';
            result.classList.add('hidden');

            try {
                const res = await fetch("<?php echo e(route('admin.telegram-settings.test')); ?>", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                        Accept: 'application/json',
                    },
                });

                const data = await res.json();

                result.textContent = data.message;
                result.className = 'mt-4 rounded-[5px] border-l-4 px-3 py-2.5 text-sm font-medium ' + (data.ok ? 'border-ok-500 bg-ok-50 text-ok-700' : 'border-critical-500 bg-critical-50 text-critical-700');
                result.classList.remove('hidden');
            } catch {
                result.textContent = 'Terjadi kesalahan jaringan saat melakukan test.';
                result.className = 'mt-4 rounded-[5px] border-l-4 px-3 py-2.5 text-sm font-medium border-critical-500 bg-critical-50 text-critical-700';
                result.classList.remove('hidden');
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalContent;
            }
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\99. Other\PROJECT\project-app\ticket-it-app\resources\views/admin/telegram-settings/show.blade.php ENDPATH**/ ?>