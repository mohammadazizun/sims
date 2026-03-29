@extends('layouts.app')

@section('content')
    <div class="card">
        <h2>Pengaturan Web Service Dapodik</h2>
        <p>Isi nama aplikasi, alamat base URL Dapodik, dan API key yang Anda salin dari menu Web Service Dapodik.</p>

        <form action="{{ route('dapodik.settings.save') }}" method="POST">
            @csrf
            <div class="field-row">
                <div class="form-group">
                    <label for="name">Nama Aplikasi</label>
                    <input id="name" name="name" value="{{ old('name', $setting->name ?? '') }}">
                </div>
                <div class="form-group">
                    <label for="base_url">Base URL Dapodik</label>
                    <input id="base_url" name="base_url" value="{{ old('base_url', $setting->base_url ?? '') }}" placeholder="http://localhost:5774">
                </div>
                <div class="form-group">
                    <label for="api_key">API Key</label>
                    <input id="api_key" name="api_key" value="{{ old('api_key', $setting->api_key ?? '') }}">
                </div>
                <div class="form-group">
                    <label for="active">Aktifkan</label>
                    <select id="active" name="active">
                        <option value="1" {{ old('active', $setting->active ?? true) ? 'selected' : '' }}>Ya</option>
                        <option value="0" {{ !old('active', $setting->active ?? true) ? 'selected' : '' }}>Tidak</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="public_search_enabled">Pencarian Publik</label>
                    <select id="public_search_enabled" name="public_search_enabled">
                        <option value="1" {{ old('public_search_enabled', $setting->public_search_enabled ?? false) ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ !old('public_search_enabled', $setting->public_search_enabled ?? false) ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="field-row">
                <div class="form-group">
                    <label for="fetch_endpoint">Fetch Endpoint</label>
                    <input id="fetch_endpoint" name="fetch_endpoint" value="{{ old('fetch_endpoint', $setting->fetch_endpoint ?? '') }}" placeholder="Contoh: ws/peserta_didik atau #PesertaDidik">
                    <small>Gunakan <code>#PesertaDidik</code> jika Web Service Dapodik menampilkan rute hash, atau masukkan path API langsung.</small>
                </div>
                <div class="form-group">
                    <label for="push_endpoint">Push Endpoint</label>
                    <input id="push_endpoint" name="push_endpoint" value="{{ old('push_endpoint', $setting->push_endpoint ?? '') }}" placeholder="Contoh: ws/peserta_didik/upload">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="button">Simpan</button>
                <a class="button button-secondary" href="{{ route('dapodik.sync.form') }}">Kembali ke Sinkronisasi</a>
            </div>
        </form>
    </div>
@endsection
