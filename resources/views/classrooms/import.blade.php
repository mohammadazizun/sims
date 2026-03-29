@extends('layouts.app')

@section('content')
    <div class="card">
        <h2>Import Data Kelas dari Dapodik</h2>
        <p>Unggah file CSV, JSON, atau ZIP yang berisi data kelas. Gunakan template berikut untuk copy/paste langsung ke Excel:</p>
        <ul>
            <li><strong>name, grade, major, academic_year, description</strong> untuk import kelas langsung.</li>
            <li><strong>kelas, tingkat, jurusan, tahun_ajaran</strong> untuk format Dapodik.</li>
        </ul>
        <p>Download template CSV contoh: <a href="/templates/classroom-import-template.csv" target="_blank">classroom-import-template.csv</a>. Buka di Excel dan mulai isian data dari baris kedua.</p>

        <form action="{{ route('classrooms.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="data_file">File Dapodik (CSV, JSON, ZIP)</label>
                <input type="file" id="data_file" name="data_file" accept=".csv,.json,.zip" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="button">Import Kelas</button>
                <a class="button button-secondary" href="{{ route('classrooms.index') }}">Kembali</a>
            </div>
        </form>
    </div>
@endsection
