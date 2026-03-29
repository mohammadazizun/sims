@extends('layouts.app')

@section('title', 'Laporan Data Siswa')

@section('content')
    <div class="row mb-3">
        <div class="col-12">
            <div class="card card-outline card-secondary">
                <div class="card-body">
                    <a class="btn btn-secondary" href="{{ route('students.index') }}"><i class="fas fa-users"></i> Daftar Siswa</a>
                    <a class="btn btn-secondary" href="{{ route('classrooms.index') }}"><i class="fas fa-chalkboard"></i> Daftar Kelas</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $totalStudents }}</h3>
                    <p>Total Siswa</p>
                </div>
                <div class="icon"><i class="fas fa-user-graduate"></i></div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">Jenis Kelamin</h3>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @foreach($byGender as $gender)
                            <li><strong>{{ $gender->gender === 'P' ? 'Perempuan' : ($gender->gender === 'L' ? 'Laki-laki' : 'Tidak Diketahui') }}:</strong> {{ $gender->total }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">Status Siswa</h3>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @foreach($byStatus as $status)
                            <li><strong>{{ $status->status ?: 'Tidak diisi' }}:</strong> {{ $status->total }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-3">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Jumlah Siswa per Kelas</h3>
                </div>
                <div class="card-body table-responsive">
                    @if($byClassroom->isEmpty())
                        <p>Belum ada kelas dengan siswa terdaftar.</p>
                    @else
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Kelas</th>
                                    <th>Jumlah Siswa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($byClassroom as $classroom)
                                    <tr>
                                        <td>{{ $classroom->name }}</td>
                                        <td>{{ $classroom->students_count }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-3">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">20 Siswa Terbaru</h3>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Kelas</th>
                                <th>Status</th>
                                <th>Tanggal Tambah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($latest as $student)
                                <tr>
                                    <td>{{ $student->full_name }}</td>
                                    <td>{{ optional($student->classroom)->name ?? '-' }}</td>
                                    <td>{{ $student->status }}</td>
                                    <td>{{ optional($student->created_at)->format('d-m-Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
