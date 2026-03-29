

<?php $__env->startSection('title', 'Masukkan Siswa ke Kelas'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-12 mb-3">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Masukkan Siswa ke Kelas <?php echo e($classroom->name); ?></h3>
                    <div class="card-tools">
                        <a class="btn btn-sm btn-secondary" href="<?php echo e(route('classrooms.show', $classroom)); ?>"><i class="fas fa-arrow-left"></i> Kembali ke Detail Kelas</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th>Nama Kelas</th>
                                        <td><?php echo e($classroom->name); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Tingkat</th>
                                        <td><?php echo e($classroom->grade ?? '-'); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th>Jurusan</th>
                                        <td><?php echo e($classroom->major ?? '-'); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Tahun Ajaran</th>
                                        <td><?php echo e($classroom->academic_year ?? '-'); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mt-3">
                        <strong>Deskripsi:</strong>
                        <p><?php echo e($classroom->description ?? '-'); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card card-outline card-secondary">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Siswa Aktif yang Tersedia</h3>
                    <form action="<?php echo e(route('classrooms.assign', $classroom)); ?>" method="GET" class="d-flex gap-2">
                        <select name="major" class="form-select form-select-sm">
                            <option value="">Semua Jurusan</option>
                            <?php $__currentLoopData = $majorOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $major): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($major); ?>" <?php echo e(isset($majorFilter) && $majorFilter === $major ? 'selected' : ''); ?>><?php echo e($major); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">Tampilkan</button>
                    </form>
                </div>
                <div class="card-body">
                    <?php if($availableStudents->isEmpty()): ?>
                        <p>Tidak ada siswa aktif dengan jurusan yang dipilih.</p>
                    <?php else: ?>
                        <div class="row g-3">
                            <?php $__currentLoopData = $availableStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-4">
                                    <div class="card draggable-student" draggable="true" data-student-id="<?php echo e($student->id); ?>">
                                        <div class="card-body">
                                            <h5 class="card-title mb-1"><?php echo e($student->full_name); ?></h5>
                                            <p class="mb-1"><strong>NISN:</strong> <?php echo e($student->nisn ?: '-'); ?></p>
                                            <p class="mb-1"><strong>Jurusan:</strong> <?php echo e($student->major ?: '-'); ?></p>
                                            <p class="mb-0"><strong>Status:</strong> <?php echo e($student->status ?: '-'); ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <div class="alert alert-info mt-3">
                            Seret siswa ke area berikut untuk memasukkan ke kelas ini.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">Daftar Siswa Kelas <?php echo e($classroom->name); ?></h3>
                </div>
                <div class="card-body table-responsive">
                    <div class="drop-zone p-3 mb-3 rounded border border-primary bg-light text-center" style="min-height: 120px;">
                        <strong>Drop siswa di sini untuk memasukkan ke kelas <?php echo e($classroom->name); ?></strong>
                    </div>
                    <?php if($classroom->students->isEmpty()): ?>
                        <p>Belum ada siswa yang terdaftar di kelas ini.</p>
                    <?php else: ?>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>NISN</th>
                                    <th>Nama</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $classroom->students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($student->nisn); ?></td>
                                        <td><?php echo e($student->full_name); ?></td>
                                        <td><?php echo e($student->gender === 'P' ? 'Perempuan' : ($student->gender === 'L' ? 'Laki-laki' : '-')); ?></td>
                                        <td><?php echo e($student->status); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <form id="assignStudentForm" action="<?php echo e(route('classrooms.assign.student', $classroom)); ?>" method="POST" class="d-none">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="student_id" id="assign_student_id" value="">
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const draggable = document.querySelectorAll('.draggable-student');
            const dropZone = document.querySelector('.drop-zone');
            const form = document.getElementById('assignStudentForm');
            const input = document.getElementById('assign_student_id');

            draggable.forEach(item => {
                item.addEventListener('dragstart', function (event) {
                    event.dataTransfer.setData('text/plain', this.dataset.studentId);
                    this.classList.add('dragging');
                });
                item.addEventListener('dragend', function () {
                    this.classList.remove('dragging');
                });
            });

            dropZone.addEventListener('dragover', function (event) {
                event.preventDefault();
                this.classList.add('bg-primary', 'text-white');
            });

            dropZone.addEventListener('dragleave', function () {
                this.classList.remove('bg-primary', 'text-white');
            });

            dropZone.addEventListener('drop', function (event) {
                event.preventDefault();
                this.classList.remove('bg-primary', 'text-white');
                const studentId = event.dataTransfer.getData('text/plain');
                if (studentId) {
                    input.value = studentId;
                    form.submit();
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\laragon\www\sims\resources\views/classrooms/assign.blade.php ENDPATH**/ ?>