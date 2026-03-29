@extends('layouts.app')

@section('title', 'Siswa Mutasi / Keluar')

@section('content')
    <div class="card mb-3">
        <div class="card-body d-flex flex-wrap gap-3 align-items-center">
            <a class="btn btn-primary btn-sm" href="{{ route('students.index') }}"><i class="fas fa-arrow-left"></i> Kembali ke Daftar Aktif</a>
            <a class="btn btn-success btn-sm" href="{{ route('students.create') }}"><i class="fas fa-plus"></i> Tambah Siswa</a>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('students.archive') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-12 col-md-4">
                    <label for="name" class="form-label">Cari Nama</label>
                    <input id="name" name="name" type="text" class="form-control" placeholder="Masukkan nama siswa" value="{{ old('name', $query ?? '') }}">
                </div>
                <div class="col-12 col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-select">
                        <option value="">Semua Status</option>
                        @foreach($statusOptions as $value)
                            <option value="{{ $value }}" {{ isset($status) && $status === $value ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <label for="graduation_year" class="form-label">Tahun Kelulusan</label>
                    <select id="graduation_year" name="graduation_year" class="form-select">
                        <option value="">Semua Tahun</option>
                        @foreach($yearOptions as $year)
                            <option value="{{ $year }}" {{ isset($graduationYear) && $graduationYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                    @if($query || $status || $graduationYear)
                        <a href="{{ route('students.archive') }}" class="btn btn-outline-secondary mt-2">Reset</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Siswa Mutasi / Keluar / Lulus</h3>
        </div>
        <div class="card-body table-responsive">
            @if($students->isEmpty())
                <p>Tidak ada siswa mutasi/keluar/lulus sesuai filter.</p>
            @else
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
                        @foreach($students as $student)
                            <tr>
                                <td>{{ $student->full_name }}</td>
                                <td>{{ optional($student->classroom)->name ?? '-' }}</td>
                                <td>{{ $student->status ?: '-' }}</td>
                                <td>{{ $student->graduation_year ?: '-' }}</td>
                                <td>{{ $student->gender === 'P' ? 'Perempuan' : ($student->gender === 'L' ? 'Laki-laki' : '-') }}</td>
                                <td>
                                    <a class="btn btn-sm btn-secondary" href="{{ route('students.show', $student) }}">Lihat</a>
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
