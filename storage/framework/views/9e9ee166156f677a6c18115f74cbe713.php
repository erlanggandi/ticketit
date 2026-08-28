<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'IT Helpdesk'); ?> — <?php echo e($siteSetting?->app_name ?? config('app.name')); ?></title>

    <?php if($siteSetting?->favicon_path): ?>
        <link rel="icon" href="<?php echo e(asset('storage/'.$siteSetting->favicon_path)); ?>">
    <?php endif; ?>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="min-h-full">
    <?php
        $ss = $siteSetting ?? (object) [
            'app_name' => config('app.name'),
            'app_short_name' => 'IT',
            'tagline' => 'Biro Dukungan Teknis',
            'form_title' => 'Lapor Masalah IT',
            'form_description' => null,
            'footer_text' => 'Formulir FRM-IT-001 · Rev.02',
            'logo_path' => null,
            'favicon_path' => null,
        ];
    ?>

    <div class="flex min-h-screen flex-col">
        <header class="border-b border-ink bg-sheet">
            <nav class="mx-auto flex h-16 max-w-3xl items-center justify-between gap-4 px-4 sm:px-6">
                <a href="<?php echo e(route('home')); ?>" class="group flex items-center gap-3" aria-label="<?php echo e($ss->app_name); ?> — beranda">
                    <?php if($ss->logo_path): ?>
                        <img src="<?php echo e(asset('storage/'.$ss->logo_path)); ?>" alt="<?php echo e($ss->app_name); ?>" class="h-9 w-9 rounded-[4px] border border-ink/30 object-contain bg-sheet p-0.5" />
                    <?php else: ?>
                        <span class="flex h-9 w-9 items-center justify-center rounded-[4px] bg-ink font-mono text-sm font-semibold tracking-tight text-white shadow-[2px_2px_0_0_var(--color-stamp-600)]">
                            <?php echo e($ss->app_short_name); ?>

                        </span>
                    <?php endif; ?>
                    <span class="flex flex-col leading-tight">
                        <span class="display text-[13px] leading-none"><?php echo e($ss->app_name); ?></span>
                        <span class="mt-1 font-mono text-[9px] font-medium uppercase tracking-[0.24em] text-muted"><?php echo e($ss->tagline); ?></span>
                    </span>
                </a>

                <div class="flex items-center gap-1 sm:gap-5">
                    <a href="<?php echo e(route('tickets.create')); ?>" aria-current="<?php echo e(request()->routeIs('tickets.create') ? 'page' : false); ?>"
                       class="px-1 py-2 font-mono text-[11px] font-semibold uppercase tracking-[0.18em] <?php echo e(request()->routeIs('tickets.create') ? 'text-ink underline decoration-signal-500 decoration-[3px] underline-offset-8' : 'text-muted hover:text-ink'); ?>">
                        Buat Tiket
                    </a>
                    <a href="<?php echo e(route('tickets.check')); ?>" aria-current="<?php echo e(request()->routeIs('tickets.check') ? 'page' : false); ?>"
                       class="px-1 py-2 font-mono text-[11px] font-semibold uppercase tracking-[0.18em] <?php echo e(request()->routeIs('tickets.check') ? 'text-ink underline decoration-signal-500 decoration-[3px] underline-offset-8' : 'text-muted hover:text-ink'); ?>">
                        Cek Status
                    </a>
                </div>
            </nav>
        </header>

        <main class="mx-auto w-full max-w-3xl flex-1 px-4 py-10 sm:px-6 sm:py-12">
            <?php echo $__env->yieldContent('content'); ?>
        </main>

        <footer class="border-t border-rule/70 pb-10 pt-5 text-center font-mono text-[10px] uppercase tracking-[0.18em] text-faint">
            <?php echo e($ss->footer_text); ?> · <?php echo e(date('Y')); ?>

        </footer>
    </div>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /var/www/html/resources/views/layouts/public.blade.php ENDPATH**/ ?>