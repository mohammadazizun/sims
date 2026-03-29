@extends('layouts.app')

@section('title', 'Cek Data Siswa')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Cek Data Siswa</h3>
        </div>
        <div class="card-body">
            <p>Masukkan NISN, NIS, atau nama siswa untuk menampilkan data secara mandiri tanpa login.</p>

            <form action="{{ route('students.check') }}" method="GET" class="mb-4">
                <div class="input-group input-group-lg">
                    <input type="text" name="q" class="form-control" placeholder="Masukkan NISN atau nama siswa" value="{{ old('q', $query ?? '') }}" required autofocus>
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Cari</button>
                    </div>
                </div>
            </form>

            @if(!$publicSearchEnabled)
                <div class="alert alert-info">Pencarian publik saat ini dinonaktifkan. Aktifkan fitur <strong>Pencarian Publik</strong> di menu Pengaturan Dapodik agar siswa dapat mencari data tanpa login.</div>
            @endif

            @if(isset($query) && $publicSearchEnabled)
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-3">
                    <div>
                        <strong>Hasil pencarian untuk:</strong> "{{ $query }}"
                    </div>
                    <div>
                        <span class="badge bg-primary">{{ $students->count() }} hasil</span>
                    </div>
                </div>

                @if($students->isEmpty())
                    <div class="alert alert-warning">Tidak ditemukan data siswa dengan kriteria tersebut. Coba masukkan NISN lengkap atau nama lengkap.</div>
                @else
                    <div class="row g-3">
                        @foreach($students as $student)
                            <div class="col-12 col-md-6">
                                <div class="card shadow-sm h-100">
                                    <div class="card-body d-flex align-items-center gap-3">
                                        <div class="flex-shrink-0">
                                            @if($student->photo_path)
                                                <img src="{{ Storage::url($student->photo_path) }}" alt="Foto {{ $student->full_name }}" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
                                            @else
                                                <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">-</div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="mb-1">{{ $student->full_name }}</h5>
                                            <p class="mb-1 text-muted">Kelas: {{ optional($student->classroom)->name ?? '-' }}</p>
                                            <p class="mb-0 text-muted">NISN: {{ $student->nisn ?: '-' }}</p>
                                        </div>
                                        <div class="text-end">
                                            <a class="btn btn-sm btn-outline-primary" href="{{ route('students.public.show', $student) }}">Lihat Detail</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection
