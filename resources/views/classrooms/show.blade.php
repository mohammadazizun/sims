@extends('layouts.app')

@section('title', 'Detail Kelas')

@section('content')
    <div class="row">
        <div class="col-12 mb-3">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Detail Kelas</h3>
                    <div class="card-tools">
                        <a class="btn btn-sm btn-warning" href="{{ route('classrooms.edit', $classroom) }}"><i class="fas fa-edit"></i> Edit</a>
                        <form action="{{ route('classrooms.destroy', $classroom) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Hapus kelas ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                        </form>
                        <a class="btn btn-sm btn-secondary" href="{{ route('classrooms.index') }}"><i class="fas fa-arrow-left"></i> Kembali</a>
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

        <div class="col-12 mb-3">
            <div class="card card-outline card-secondary">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Masukkan Siswa</h3>
                    <a href="{{ route('classrooms.assign', $classroom) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-users"></i> Masukkan Siswa per Jurusan
                    </a>
                </div>
                <div class="card-body">
                    <p>Ini adalah daftar siswa yang saat ini terdaftar di kelas ini. Untuk menambahkan siswa berdasarkan jurusan, gunakan tombol di atas.</p>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">Daftar Siswa Kelas</h3>
                </div>
                <div class="card-body table-responsive">
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

@endsection
