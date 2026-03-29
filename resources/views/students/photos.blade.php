@extends('layouts.app')

@section('title', 'Upload Foto Kolektif')

@section('content')
    <div class="card card-info card-outline">
        <div class="card-header">
            <h3 class="card-title">Upload Foto Kolektif</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('students.photos.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="photos">Pilih Foto Siswa</label>
                    <input type="file" id="photos" name="photos[]" multiple class="form-control-file">
                    <small class="form-text text-muted">Unggah banyak foto sekaligus. Nama file harus sesuai NISN, NIK, atau nama siswa.</small>
                </div>
                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-cloud-upload-alt"></i> Unggah Foto</button>
                    <a href="{{ route('students.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali ke Daftar Siswa</a>
                </div>
            </form>
        </div>
    </div>
@endsection
