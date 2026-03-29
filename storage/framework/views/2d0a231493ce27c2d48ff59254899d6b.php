

<?php $__env->startSection('title', 'Siswa Mutasi / Keluar'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card mb-3">
        <div class="card-body d-flex flex-wrap gap-3 align-items-center">
            <a class="btn btn-primary btn-sm" href="<?php echo e(route('students.index')); ?>"><i class="fas fa-arrow-left"></i> Kembali ke Daftar Aktif</a>
            <a class="btn btn-success btn-sm" href="<?php echo e(route('students.create')); ?>"><i class="fas fa-plus"></i> Tambah Siswa</a>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form action="<?php echo e(route('students.archive')); ?>" method="GET" class="row g-2 align-items-end">
                <div class="col-12 col-md-4">
                    <label for="name" class="form-label">Cari Nama</label>
                    <input id="name" name="name" type="text" class="form-control" placeholder="Masukkan nama siswa" value="<?php echo e(old('name', $query ?? '')); ?>">
                </div>
                <div class="col-12 col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value); ?>" <?php echo e(isset($status) && $status === $value ? 'selected' : ''); ?>><?php echo e($value); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <label for="graduation_year" class="form-label">Tahun Kelulusan</label>
                    <select id="graduation_year" name="graduation_year" class="form-select">
                        <option value="">Semua Tahun</option>
                        <?php $__currentLoopData = $yearOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($year); ?>" <?php echo e(isset($graduationYear) && $graduationYear == $year ? 'selected' : ''); ?>><?php echo e($year); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-12 col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                    <?php if($query || $status || $graduationYear): ?>
                        <a href="<?php echo e(route('students.archive')); ?>" class="btn btn-outline-secondary mt-2">Reset</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Siswa Mutasi / Keluar / Lulus</h3>
        </div>
        <div class="card-body table-responsive">
            <?php if($students->isEmpty()): ?>
                <p>Tidak ada siswa mutasi/keluar/lulus sesuai filter.</p>
            <?php else: ?>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Status</th>
                            <th>Tahun Kelulusan</th>
                            <th>Jenis Kelamin</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($student->full_name); ?></td>
                                <td><?php echo e(optional($student->classroom)->name ?? '-'); ?></td>
                                <td><?php echo e($student->status ?: '-'); ?></td>
                                <td><?php echo e($student->graduation_year ?: '-'); ?></td>
                                <td><?php echo e($student->gender === 'P' ? 'Perempuan' : ($student->gender === 'L' ? 'Laki-laki' : '-')); ?></td>
                                <td>
                                    <a class="btn btn-sm btn-secondary" href="<?php echo e(route('students.show', $student)); ?>">Lihat</a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>

                <div class="mt-3">
                    <?php echo e($students->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laragon\www\sims\resources\views/students/archive.blade.php ENDPATH**/ ?>