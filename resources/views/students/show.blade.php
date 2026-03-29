@extends('layouts.app')

@section('title', 'Detail Siswa')

@section('content')
    <div class="row">
        <div class="col-12 mb-3">
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="card-title">Detail Siswa</h3>
                        <p class="text-muted mb-0">Informasi lengkap mengenai siswa. Anda hanya dapat melihat data.</p>
                    </div>
                    <div class="card-tools">
                        @if(auth()->check())
                            <a class="btn btn-sm btn-warning" href="{{ route('students.edit', $student) }}"><i class="fas fa-edit"></i> Edit</a>
                            <a class="btn btn-sm btn-secondary" href="{{ route('students.index') }}"><i class="fas fa-arrow-left"></i> Kembali</a>
                        @else
                            <a class="btn btn-sm btn-secondary" href="{{ route('students.check') }}"><i class="fas fa-arrow-left"></i> Kembali</a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-12 col-md-4 text-center">
                            <div class="mb-3">
                                @if($student->photo_path)
                                    <img src="{{ Storage::url($student->photo_path) }}" alt="Foto {{ $student->full_name }}" class="img-fluid rounded shadow-sm" style="max-height: 320px; width: auto; max-width: 100%; object-fit: cover;">
                                @else
                                    <div class="bg-secondary rounded text-white d-flex align-items-center justify-content-center" style="min-height: 260px;">
                                        <span class="h5">No Photo</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-12 col-md-8">
                            <div class="row row-cols-1 row-cols-md-2 g-3">
                                <div class="col">
                                    <div class="border rounded p-3 h-100">
                                        <h5 class="mb-3">{{ $student->full_name }}</h5>
                                        <p class="mb-2 text-muted">Kelas: {{ optional($student->classroom)->name ?? '-' }}</p>
                                        <p class="mb-2 text-muted">Jurusan: {{ $student->major ?: '-' }}</p>
                                        <p class="mb-2 text-muted">NISN: {{ $student->nisn ?: '-' }}</p>
                                        <p class="mb-2 text-muted">NIS: {{ $student->nis ?: '-' }}</p>
                                        <p class="mb-0 text-muted">NIK: {{ $student->nik ?: '-' }}</p>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="border rounded p-3 h-100">
                                        <p class="mb-2"><strong>Jenis Kelamin:</strong> {{ $student->gender === 'P' ? 'Perempuan' : ($student->gender === 'L' ? 'Laki-laki' : '-') }}</p>
                                        <p class="mb-2"><strong>Status:</strong> {{ $student->status ?: '-' }}</p>
                                        <p class="mb-2"><strong>Telepon:</strong> {{ $student->phone ?: '-' }}</p>
                                        <p class="mb-0"><strong>Email:</strong> {{ $student->email ?: '-' }}</p>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="border rounded p-3 h-100">
                                        <p class="mb-2"><strong>Jenis Tinggal:</strong> {{ $student->residence_type ?: '-' }}</p>
                                        <p class="mb-2"><strong>Transportasi:</strong> {{ $student->transportation ?: '-' }}</p>
                                        <p class="mb-2"><strong>Golongan Darah:</strong> {{ $student->blood_type ?: '-' }}</p>
                                        <p class="mb-0"><strong>HP Orang Tua:</strong> {{ $student->parent_phone ?: '-' }}</p>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="border rounded p-3 h-100">
                                        <p class="mb-2"><strong>Tempat Lahir:</strong> {{ $student->birth_place ?: '-' }}</p>
                                        <p class="mb-2"><strong>Tanggal Lahir:</strong> {{ optional($student->birth_date)->format('d-m-Y') ?: '-' }}</p>
                                        <p class="mb-2"><strong>Agama:</strong> {{ $student->religion ?: '-' }}</p>
                                        <p class="mb-0"><strong>Anak Ke-:</strong> {{ $student->child_order ?: '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-lg-2 g-3">
            <div class="col">
                <div class="card card-outline card-info shadow-sm h-100">
                    <div class="card-header">
                        <h3 class="card-title">Alamat</h3>
                    </div>
                    <div class="card-body">
                        <p class="mb-1">{{ $student->address ?: '-' }}</p>
                        <p class="mb-1">RT {{ $student->rt ?: '-' }} / RW {{ $student->rw ?: '-' }}</p>
                        <p class="mb-1">{{ $student->village ?: '-' }}, {{ $student->district ?: '-' }}</p>
                        <p class="mb-0">{{ $student->city ?: '-' }}, {{ $student->province ?: '-' }} {{ $student->postal_code ?: '' }}</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card card-outline card-info shadow-sm h-100">
                    <div class="card-header">
                        <h3 class="card-title">Data Sekolah</h3>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-sm-4">Sekolah Asal</dt>
                            <dd class="col-sm-8">{{ $student->previous_school ?: '-' }}</dd>
                            <dt class="col-sm-4">Tahun Lulus</dt>
                            <dd class="col-sm-8">{{ $student->graduation_year ?: '-' }}</dd>
                            <dt class="col-sm-4">Tanggal Masuk</dt>
                            <dd class="col-sm-8">{{ optional($student->entry_date)->format('d-m-Y') ?: '-' }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card card-outline card-success shadow-sm h-100">
                    <div class="card-header">
                        <h3 class="card-title">Data Orang Tua / Wali</h3>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-sm-4">Ayah</dt>
                            <dd class="col-sm-8">{{ $student->father_name ?: '-' }} ({{ $student->father_occupation ?: '-' }})</dd>
                            <dt class="col-sm-4">NIK Ayah</dt>
                            <dd class="col-sm-8">{{ $student->father_nik ?: '-' }}</dd>
                            <dt class="col-sm-4">Ibu</dt>
                            <dd class="col-sm-8">{{ $student->mother_name ?: '-' }} ({{ $student->mother_occupation ?: '-' }})</dd>
                            <dt class="col-sm-4">NIK Ibu</dt>
                            <dd class="col-sm-8">{{ $student->mother_nik ?: '-' }}</dd>
                            <dt class="col-sm-4">Wali</dt>
                            <dd class="col-sm-8">{{ $student->guardian_name ?: '-' }} ({{ $student->guardian_occupation ?: '-' }})</dd>
                            <dt class="col-sm-4">NIK Wali</dt>
                            <dd class="col-sm-8">{{ $student->guardian_nik ?: '-' }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card card-outline card-warning shadow-sm h-100">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Tambahan</h3>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-sm-4">HP Orang Tua</dt>
                            <dd class="col-sm-8">{{ $student->parent_phone ?: '-' }}</dd>
                            <dt class="col-sm-4">Jenis Bantuan</dt>
                            <dd class="col-sm-8">{{ $student->assistance_type ?: '-' }}</dd>
                            <dt class="col-sm-4">Nomor Bantuan</dt>
                            <dd class="col-sm-8">{{ $student->assistance_number ?: '-' }}</dd>
                            <dt class="col-sm-4">Anak Ke-</dt>
                            <dd class="col-sm-8">{{ $student->child_order ?: '-' }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
