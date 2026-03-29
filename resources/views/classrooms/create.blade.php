@extends('layouts.app')

@section('title', 'Tambah Kelas')

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">Tambah Kelas Baru</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('classrooms.store') }}" method="POST">
                @csrf
                @include('classrooms._form')
                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                    <a class="btn btn-secondary" href="{{ route('classrooms.index') }}"><i class="fas fa-arrow-left"></i> Kembali</a>
                </div>
            </form>
        </div>
    </div>
@endsection
