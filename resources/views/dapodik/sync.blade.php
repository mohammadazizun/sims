@extends('layouts.app')

@section('content')
    <div class="card">
        <h2>Sinkronisasi Dapodik</h2>
        <p>Gunakan fitur ini untuk mengambil data siswa langsung dari Dapodik atau mengunggah data siswa dari aplikasi ini ke Dapodik.</p>

        @if(!empty($setting))
            <div class="field-row">
                <div class="card">
                    <p><strong>Nama Aplikasi:</strong> {{ $setting->name ?? '-' }}</p>
                    <p><strong>Base URL:</strong> {{ $setting->base_url }}</p>
                    <p><strong>API Key:</strong> {{ $setting->api_key ? 'Tersimpan' : '-' }}</p>
                    <p><strong>Status:</strong> {{ $setting->active ? 'Aktif' : 'Nonaktif' }}</p>
                </div>
            </div>
        @else
            <div class="card">
                <p class="text-muted">Pengaturan Web Service Dapodik belum dikonfigurasi. Silakan simpan pengaturan terlebih dahulu.</p>
            </div>
        @endif

        <div class="form-actions">
            <a class="button" href="{{ route('dapodik.settings') }}">Pengaturan Web Service</a>
            <a class="button button-secondary" href="{{ route('students.import') }}">Import dari File</a>
            <a class="button button-secondary" href="{{ route('students.index') }}">Daftar Siswa</a>
        </div>

        @if(!empty($setting))
            <div class="card">
                <h3>Sinkronisasi Dapodik</h3>
                <form action="{{ route('dapodik.sync.fetch') }}" method="POST" style="margin-bottom: 12px;">
                    @csrf
                    <button type="submit" class="button">Tarik Data Siswa dari Dapodik</button>
                </form>
                <form action="{{ route('dapodik.sync.push') }}" method="POST">
                    @csrf
                    <button type="submit" class="button button-secondary">Unggah Data Siswa ke Dapodik</button>
                </form>
            </div>
        @endif
    </div>
@endsection
