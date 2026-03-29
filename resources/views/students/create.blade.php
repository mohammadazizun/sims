@extends('layouts.app')

@section('title', 'Tambah Data Siswa')

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">Tambah Data Siswa</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('students._form', ['student' => new App\Models\Student])
                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                    <a class="btn btn-secondary" href="{{ route('students.index') }}"><i class="fas fa-arrow-left"></i> Kembali</a>
                </div>
            </form>
        </div>
    </div>
@endsection
