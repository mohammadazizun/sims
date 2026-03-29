@extends('layouts.app')

@section('title', 'Pilih Kelas Tujuan')

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h3 class="card-title">Pilih Kelas Tujuan untuk {{ $student->full_name }}</h3>
                <p class="text-muted mb-0">Siswa saat ini berada di kelas: {{ optional($student->classroom)->name ?? '-' }}</p>
            </div>
            <a href="{{ route('students.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{ route('students.promote', $student) }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="classroom_id">Tujuan Kelas</label>
                    <select id="classroom_id" name="classroom_id" class="form-control" required>
                        <option value="">Pilih kelas tujuan</option>
                        @foreach($classrooms as $classroom)
                            <option value="{{ $classroom->id }}" {{ old('classroom_id') == $classroom->id ? 'selected' : '' }}>{{ $classroom->name }}{{ $classroom->grade ? ' - '.$classroom->grade : '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Naikkan ke Kelas Ini</button>
                </div>
            </form>
        </div>
    </div>
@endsection
