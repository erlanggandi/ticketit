<?php $__env->startSection('title', 'Pengaturan Akun'); ?>
<?php $__env->startSection('page_title', 'Pengaturan Akun'); ?>
<?php $__env->startSection('page_subtitle', 'Konfigurasi kebijakan autentikasi dan sesi'); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-2xl">
        <div class="rounded-[5px] border border-dashed border-rule bg-paper">
            <div class="flex items-center justify-between border-b border-dashed border-rule bg-paper/50 px-6 py-3">
                <p class="font-mono text-[10px] uppercase tracking-[0.14em] text-muted">Setting akun &amp; keamanan</p>
            </div>

            <div class="px-6 py-4">
                <?php if(session('success')): ?>
                    <div class="mb-3 rounded-[4px] border border-stamp-300 bg-stamp-50 px-4 py-3 text-sm text-stamp-900"><?php echo e(session('success')); ?></div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                    <div class="mb-3 rounded-[4px] border border-critical-300 bg-critical-50 px-4 py-3 text-sm text-critical-900"><?php echo e(implode('<br>', $errors->all())); ?></div>
                <?php endif; ?>
            </div>

            <dialog id="auth-settings-dialog" class="max-w-md mx-auto rounded-md border border-ink bg-sheet p-0 shadow-[var(--shadow-pop)] backdrop:bg-ink/55">
                <div class="flex items-center justify-between gap-4 border-b border-ink px-6 py-3.5">
                    <h3 class="font-mono text-[11px] font-semibold uppercase tracking-[0.16em] text-ink">Pengaturan Akun</h3>
                    <button type="button" onclick="closeDialog('auth-settings-dialog')" class="-m-1 rounded-[4px] p-1 text-muted transition hover:text-critical-600" aria-label="Tutup">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <form method="POST" id="auth-settings-form" action="<?php echo e(route('admin.auth-settings.update')); ?>" data-disable-on-submit class="space-y-6">
                        <?php echo csrf_field(); ?>

                        <div>
                            <label for="password_min_length" class="label">Panjang Password Minimal <span class="text-critical-500" aria-hidden="true">*</span></label>
                            <input id="password_min_length" type="number" name="password_min_length" min="4" max="72" value="<?php echo e(old('password_min_length', $setting?->password_min_length ?? 8)); ?>" class="field" required />
                        </div>

                        <div class="space-y-2">
                            <p class="font-mono text-[10px] uppercase tracking-[0.12em] text-muted">Persyaratan Password</p>
                            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="password_require_uppercase" value="1" <?php if(old('password_require_uppercase', $setting?->password_require_uppercase ?? true)): echo 'checked'; endif; ?> /> Butuh Huruf Besar</label>
                            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="password_require_lowercase" value="1" <?php if(old('password_require_lowercase', $setting?->password_require_lowercase ?? true)): echo 'checked'; endif; ?> /> Butuh Huruf Kecil</label>
                            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="password_require_number" value="1" <?php if(old('password_require_number', $setting?->password_require_number ?? true)): echo 'checked'; endif; ?> /> Butuh Angka</label>
                            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="password_require_symbol" value="1" <?php if(old('password_require_symbol', $setting?->password_require_symbol ?? true)): echo 'checked'; endif; ?> /> Butuh Simbol (@, #, $, dll)</label>
                        </div>

                        <div>
                            <label for="login_attempts_before_lockout" class="label">Coba Login Maksimal <span class="text-critical-500" aria-hidden="true">*</span></label>
                            <input id="login_attempts_before_lockout" type="number" name="login_attempts_before_lockout" min="1" value="<?php echo e(old('login_attempts_before_lockout', $setting?->login_attempts_before_lockout ?? 5)); ?>" class="field" required />
                        </div>

                        <div>
                            <label for="lockout_duration_minutes" class="label">Durasi Kunci (menit) <span class="text-critical-500" aria-hidden="true">*</span></label>
                            <input id="lockout_duration_minutes" type="number" name="lockout_duration_minutes" min="1" value="<?php echo e(old('lockout_duration_minutes', $setting?->lockout_duration_minutes ?? 30)); ?>" class="field" required />
                        </div>

                        <div>
                            <label for="session_timeout_minutes" class="label">Waktu Habis Session (menit) <span class="text-critical-500" aria-hidden="true">*</span></label>
                            <input id="session_timeout_minutes" type="number" name="session_timeout_minutes" min="1" value="<?php echo e(old('session_timeout_minutes', $setting?->session_timeout_minutes ?? 60)); ?>" class="field" required />
                        </div>

                        <div>
                            <label for="remember_me_duration_minutes" class="label">Remember Me Durasi (menit) <span class="text-critical-500" aria-hidden="true">*</span></label>
                            <input id="remember_me_duration_minutes" type="number" name="remember_me_duration_minutes" min="1" value="<?php echo e(old('remember_me_duration_minutes', $setting?->remember_me_duration_minutes ?? 1440)); ?>" class="field" required />
                        </div>

                        <div class="flex justify-end gap-2 pt-4 border-t border-dashed border-rule">
                            <button type="button" onclick="closeDialog('auth-settings-dialog')" class="rounded-[4px] border border-rule bg-paper px-4 py-2 text-sm text-muted transition hover:border-stamp-400 hover:text-stamp-800">Batal</button>
                            <button type="submit" class="rounded-[4px] bg-stamp-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-stamp-800">Simpan Pengaturan</button>
                        </div>
                    </form>
                </div>
            </dialog>
        </div>
    </div>

    <script>
        function closeDialog(id) { document.getElementById(id).close(); }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\99. Other\PROJECT\project-app\ticket-it-app\resources\views/admin/auth-settings/show.blade.php ENDPATH**/ ?>