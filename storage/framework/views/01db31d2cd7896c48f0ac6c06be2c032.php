

<?php $__env->startSection('content'); ?>
    <div class="card">
        <h2>Edit Kelas</h2>
        <form action="<?php echo e(route('classrooms.update', $classroom)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <?php echo $__env->make('classrooms._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <div class="form-actions">
                <button type="submit" class="button">Perbarui</button>
                <a class="button button-secondary" href="<?php echo e(route('classrooms.show', $classroom)); ?>">Kembali</a>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laragon\www\sims\resources\views/classrooms/edit.blade.php ENDPATH**/ ?>