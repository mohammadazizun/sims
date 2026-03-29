@extends('layouts.app')

@section('content')
    <div class="card">
        <h2>Edit Kelas</h2>
        <form action="{{ route('classrooms.update', $classroom) }}" method="POST">
            @csrf
            @method('PUT')
            @include('classrooms._form')
            <div class="form-actions">
                <button type="submit" class="button">Perbarui</button>
                <a class="button button-secondary" href="{{ route('classrooms.show', $classroom) }}">Kembali</a>
            </div>
        </form>
    </div>
@endsection
