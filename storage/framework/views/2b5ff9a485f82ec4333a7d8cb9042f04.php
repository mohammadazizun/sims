

<?php $__env->startSection('title', 'Pilih Kelas Tujuan'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card card-primary card-outline">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h3 class="card-title">Pilih Kelas Tujuan untuk <?php echo e($student->full_name); ?></h3>
                <p class="text-muted mb-0">Siswa saat ini berada di kelas: <?php echo e(optional($student->classroom)->name ?? '-'); ?></p>
            </div>
            <a href="<?php echo e(route('students.index')); ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('students.promote', $student)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="form-group mb-3">
                    <label for="classroom_id">Tujuan Kelas</label>
                    <select id="classroom_id" name="classroom_id" class="form-control" required>
                        <option value="">Pilih kelas tujuan</option>
                        <?php $__currentLoopData = $classrooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classroom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($classroom->id); ?>" <?php echo e(old('classroom_id') == $classroom->id ? 'selected' : ''); ?>><?php echo e($classroom->name); ?><?php echo e($classroom->grade ? ' - '.$classroom->grade : ''); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Naikkan ke Kelas Ini</button>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laragon\www\sims\resources\views/students/promote.blade.php ENDPATH**/ ?>