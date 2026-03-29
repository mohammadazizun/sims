@extends('layouts.app')

@section('title', 'Masukkan Siswa ke Kelas')

@section('content')
    <div class="row">
        <div class="col-12 mb-3">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Masukkan Siswa ke Kelas {{ $classroom->name }}</h3>
                    <div class="card-tools">
                        <a class="btn btn-sm btn-secondary" href="{{ route('classrooms.show', $classroom) }}"><i class="fas fa-arrow-left"></i> Kembali ke Detail Kelas</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th>Nama Kelas</th>
                                        <td>{{ $classroom->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tingkat</th>
                                        <td>{{ $classroom->grade ?? '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th>Jurusan</th>
                                        <td>{{ $classroom->major ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tahun Ajaran</th>
                                        <td>{{ $classroom->academic_year ?? '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mt-3">
                        <strong>Deskripsi:</strong>
                        <p>{{ $classroom->description ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card card-outline card-secondary">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Siswa Aktif yang Tersedia</h3>
                    <form action="{{ route('classrooms.assign', $classroom) }}" method="GET" class="d-flex gap-2">
                        <select name="major" class="form-select form-select-sm">
                            <option value="">Semua Jurusan</option>
                            @foreach($majorOptions as $major)
                                <option value="{{ $major }}" {{ isset($majorFilter) && $majorFilter === $major ? 'selected' : '' }}>{{ $major }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">Tampilkan</button>
                    </form>
                </div>
                <div class="card-body">
                    @if($availableStudents->isEmpty())
                        <p>Tidak ada siswa aktif dengan jurusan yang dipilih.</p>
                    @else
                        <div class="row g-3">
                            @foreach($availableStudents as $student)
                                <div class="col-md-4">
                                    <div class="card draggable-student" draggable="true" data-student-id="{{ $student->id }}">
                                        <div class="card-body">
                                            <h5 class="card-title mb-1">{{ $student->full_name }}</h5>
                                            <p class="mb-1"><strong>NISN:</strong> {{ $student->nisn ?: '-' }}</p>
                                            <p class="mb-1"><strong>Jurusan:</strong> {{ $student->major ?: '-' }}</p>
                                            <p class="mb-0"><strong>Status:</strong> {{ $student->status ?: '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="alert alert-info mt-3">
                            Seret siswa ke area berikut untuk memasukkan ke kelas ini.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">Daftar Siswa Kelas {{ $classroom->name }}</h3>
                </div>
                <div class="card-body table-responsive">
                    <div class="drop-zone p-3 mb-3 rounded border border-primary bg-light text-center" style="min-height: 120px;">
                        <strong>Drop siswa di sini untuk memasukkan ke kelas {{ $classroom->name }}</strong>
                    </div>
                    @if($classroom->students->isEmpty())
                        <p>Belum ada siswa yang terdaftar di kelas ini.</p>
                    @else
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
                                @foreach($classroom->students as $student)
                                    <tr>
                                        <td>{{ $student->nisn }}</td>
                                        <td>{{ $student->full_name }}</td>
                                        <td>{{ $student->gender === 'P' ? 'Perempuan' : ($student->gender === 'L' ? 'Laki-laki' : '-') }}</td>
                                        <td>{{ $student->status }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <form id="assignStudentForm" action="{{ route('classrooms.assign.student', $classroom) }}" method="POST" class="d-none">
        @csrf
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
@endsection
