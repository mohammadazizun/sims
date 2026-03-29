@extends('layouts.app')

@section('title', 'Edit Data Siswa')

@section('content')
    <div class="card card-warning card-outline">
        <div class="card-header">
            <h3 class="card-title">Edit Data Siswa</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('students.update', $student) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('students._form')
                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Perbarui</button>
                    <a class="btn btn-secondary" href="{{ route('students.show', $student) }}"><i class="fas fa-arrow-left"></i> Kembali</a>
                </div>
            </form>
        </div>
    </div>
@endsection
