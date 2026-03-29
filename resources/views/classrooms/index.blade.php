@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="form-actions">
            <a class="button" href="{{ route('classrooms.create') }}">Tambah Kelas</a>
            <a class="button button-secondary" href="{{ route('classrooms.import.form') }}">Import Kelas</a>
            <a class="button button-secondary" href="{{ route('students.index') }}">Daftar Siswa</a>
        </div>
    </div>

    <div class="card">
        <h2>Daftar Kelas</h2>

        @if($classrooms->isEmpty())
            <p>Belum ada kelas terdaftar. Buat kelas baru untuk mengelompokkan siswa.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Nama Kelas</th>
                        <th>Tingkat</th>
                        <th>Jurusan</th>
                        <th>Tahun Ajaran</th>
                        <th>Jumlah Siswa</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($classrooms as $classroom)
                        <tr>
                            <td>{{ $classroom->name }}</td>
                            <td>{{ $classroom->grade }}</td>
                            <td>{{ $classroom->major }}</td>
                            <td>{{ $classroom->academic_year }}</td>
                            <td>{{ $classroom->students()->count() }}</td>
                            <td>
                                <a class="button button-secondary" href="{{ route('classrooms.show', $classroom) }}">Lihat</a>
                                <a class="button" href="{{ route('classrooms.edit', $classroom) }}">Edit</a>
                                <form action="{{ route('classrooms.destroy', $classroom) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Hapus kelas ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="button button-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="margin-top: 16px;">{{ $classrooms->links() }}</div>
        @endif
    </div>
@endsection
