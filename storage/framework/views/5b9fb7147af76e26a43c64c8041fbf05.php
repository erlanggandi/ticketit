<?php if($paginator->hasPages()): ?>
    <nav role="navigation" aria-label="Paginasi" class="flex flex-wrap items-center justify-between gap-3">
        <p class="font-mono text-[10px] uppercase tracking-[0.14em] text-faint">
            Menampilkan <?php echo e($paginator->firstItem()); ?>&ndash;<?php echo e($paginator->lastItem()); ?> dari <?php echo e($paginator->total()); ?> data
        </p>

        <div class="flex items-center gap-1">
            
            <?php if($paginator->onFirstPage()): ?>
                <span class="pointer-events-none select-none rounded-[4px] border border-transparent px-2.5 py-1 font-mono text-[10px] font-semibold uppercase tracking-wide text-faint/50">&larr; Prev</span>
            <?php else: ?>
                <a href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev" aria-label="Halaman sebelumnya"
                    class="rounded-[4px] border border-transparent px-2.5 py-1 font-mono text-[10px] font-semibold uppercase tracking-wide text-stamp-700 transition hover:bg-stamp-50 hover:text-stamp-900">&larr; Prev</a>
            <?php endif; ?>

            <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(is_string($element)): ?>
                    <span class="px-1 font-mono text-[10px] text-faint"><?php echo e($element); ?></span>
                <?php endif; ?>

                <?php if(is_array($element)): ?>
                    <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($page == $paginator->currentPage()): ?>
                            <span aria-current="page"
                                class="rounded-[4px] border border-stamp-600 bg-stamp-600 px-2.5 py-1 font-mono text-[11px] font-semibold tabular-nums text-white"><?php echo e($page); ?></span>
                        <?php else: ?>
                            <a href="<?php echo e($url); ?>" aria-label="Ke halaman <?php echo e($page); ?>"
                                class="rounded-[4px] border border-rule bg-sheet px-2.5 py-1 font-mono text-[11px] tabular-nums text-muted transition hover:bg-paper hover:text-stamp-700"><?php echo e($page); ?></a>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            
            <?php if($paginator->hasMorePages()): ?>
                <a href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next" aria-label="Halaman berikutnya"
                    class="rounded-[4px] border border-transparent px-2.5 py-1 font-mono text-[10px] font-semibold uppercase tracking-wide text-stamp-700 transition hover:bg-stamp-50 hover:text-stamp-900">Next &rarr;</a>
            <?php else: ?>
                <span class="pointer-events-none select-none rounded-[4px] border border-transparent px-2.5 py-1 font-mono text-[10px] font-semibold uppercase tracking-wide text-faint/50">Next &rarr;</span>
            <?php endif; ?>
        </div>
    </nav>
<?php elseif($paginator->total() > 0): ?>
    <p class="font-mono text-[10px] uppercase tracking-[0.14em] text-faint">
        Menampilkan <?php echo e($paginator->firstItem()); ?>&ndash;<?php echo e($paginator->lastItem()); ?> dari <?php echo e($paginator->total()); ?> data
    </p>
<?php endif; ?>
<?php /**PATH D:\99. Other\PROJECT\project-app\ticket-it-app\resources\views/pagination/docket.blade.php ENDPATH**/ ?>