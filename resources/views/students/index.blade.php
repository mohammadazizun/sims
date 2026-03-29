@extends('layouts.app')

@section('title', 'Daftar Siswa')

@section('content')
    <div class="card mb-3">
        <div class="card-body d-flex flex-wrap gap-3 align-items-center">
            <a class="btn btn-primary btn-sm" href="{{ route('students.create') }}"><i class="fas fa-plus"></i> Tambah Siswa</a> &nbsp;
            <a class="btn btn-success btn-sm" href="{{ route('students.import.form') }}"><i class="fas fa-file-import"></i> Import Dapodik</a> &nbsp;
            <a class="btn btn-info btn-sm" href="{{ route('students.photos.form') }}"><i class="fas fa-image"></i> Upload Foto Kolektif</a> &nbsp;
            <a class="btn btn-warning btn-sm" href="{{ route('students.archive') }}"><i class="fas fa-archive"></i> Siswa Keluar / Mutasi</a>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('students.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-12 col-md-4">
                    <label for="name" class="form-label">Cari Nama Siswa</label>
                    <input id="name" name="name" type="text" class="form-control" placeholder="Masukkan nama siswa" value="{{ old('name', $query ?? '') }}">
                </div>
                <div class="col-12 col-md-3">
                    <label for="classroom_id" class="form-label">Filter Kelas</label>
                    <select id="classroom_id" name="classroom_id" class="form-select">
                        <option value="">Semua Kelas</option>
                        @foreach($classrooms as $classroom)
                            <option value="{{ $classroom->id }}" {{ isset($classroomId) && $classroomId == $classroom->id ? 'selected' : '' }}>{{ $classroom->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <label for="per_page" class="form-label">Tampil per halaman</label>
                    <select id="per_page" name="per_page" class="form-select">
                        @foreach($allowedPerPage as $option)
                            <option value="{{ $option }}" {{ ($perPage ?? 20) == $option ? 'selected' : '' }}>{{ $option }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3 d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Terapkan</button>
                    @if($query || $classroomId)
                        <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">Reset</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Siswa</h3>
        </div>
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
                <div>
                    @if($students->count())
                        <strong>Menampilkan</strong> {{ $students->firstItem() }}–{{ $students->lastItem() }} siswa aktif
                    @else
                        <strong>Menampilkan</strong> 0 siswa aktif
                    @endif
                </div>
                <div class="text-muted">
                    Per halaman: {{ $perPage ?? 20 }}
                </div>
            </div>
            <div class="table-responsive">
                @if($students->isEmpty())
                    <p>Tidak ada data siswa. Tambahkan siswa baru atau import data Dapodik.</p>
                @else
                    <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>NISN</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Jurusan</th>
                            <th>Jenis Kelamin</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                            <tr>
                                <td style="width: 80px;">
                                    @if($student->photo_path)
                                        <img src="{{ Storage::url($student->photo_path) }}" alt="Foto {{ $student->full_name }}" class="img-thumbnail" style="max-width: 60px; max-height: 60px;">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $student->nisn }}</td>
                                <td>{{ $student->full_name }}</td>
                                <td>{{ optional($student->classroom)->name ?? '-' }}</td>
                                <td>{{ $student->major ?: '-' }}</td>
                                <td>{{ $student->gender === 'P' ? 'Perempuan' : ($student->gender === 'L' ? 'Laki-laki' : '-') }}</td>
                                <td>{{ $student->status ?: '-' }}</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        <a class="btn btn-sm btn-secondary" href="{{ route('students.show', $student) }}">Lihat</a> &nbsp;
                                        <a class="btn btn-sm btn-warning" href="{{ route('students.edit', $student) }}">Edit</a> &nbsp;
                                        @if($student->classroom && (!$student->status || $student->status === 'Aktif'))
                                            <a class="btn btn-sm btn-info" href="{{ route('students.promote.form', $student) }}" title="Pilih kelas tujuan">Naik Kelas</a> &nbsp;
                                        @endif
                                        <form action="{{ route('students.graduate', $student) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin meluluskan siswa ini?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Luluskan</button> &nbsp;
                                        </form>
                                        <form action="{{ route('students.destroy', $student) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Hapus siswa ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $students->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
