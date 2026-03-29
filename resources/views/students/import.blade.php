@extends('layouts.app')

@section('content')
    <div class="card">
        <h2>Import Data Siswa dari Dapodik</h2>
        <p>Unggah file CSV, JSON, atau ZIP yang berisi data siswa Dapodik. Gunakan template berikut untuk copy/paste langsung ke Excel:</p>
        <ul>
            <li><strong>nisn, nis, nik, full_name, gender, birth_place, birth_date, religion, blood_type, address, rt, rw, village, district, city, province, postal_code, phone, email, family_card_number, child_order, father_name, father_occupation, mother_name, mother_occupation, guardian_name, guardian_occupation, previous_school, graduation_year, entry_date, status</strong></li>
            <li><strong>classroom_name, classroom_grade, classroom_major, classroom_academic_year</strong> untuk mengaitkan siswa ke kelas.</li>
        </ul>
        <p>Gunakan satu kolom <strong>birth_date</strong> dengan format <code>YYYY-MM-DD</code>. Format lain yang umum seperti <code>DD/MM/YYYY</code> juga akan dicoba.</p>
        <p>Download template CSV contoh: <a href="/templates/student-import-template.csv" target="_blank">student-import-template.csv</a>. Buka di Excel dan mulai isian data dari baris kedua.</p>

        <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(!empty($classroom))
                <div class="alert alert-info">
                    Import ini akan memasukkan siswa ke kelas <strong>{{ $classroom->name }}</strong>.
                </div>
            @endif
            <input type="hidden" name="classroom_id" value="{{ $classroomId ?? '' }}">
            <div class="form-group">
                <label for="data_file">File Dapodik (CSV, JSON, ZIP)</label>
                <input type="file" id="data_file" name="data_file" accept=".csv,.json,.zip" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="button">Import</button>
                <a class="button button-secondary" href="{{ route('students.index') }}">Kembali</a>
                <a class="button button-secondary" href="{{ route('dapodik.sync.form') }}">Sinkronkan dengan Dapodik</a>
            </div>
        </form>
    </div>
@endsection
