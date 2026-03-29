@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="login-logo">
        <a href="{{ route('login') }}"><b>SIMS</b> Admin</a>
    </div>
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <p class="login-box-msg">Masuk menggunakan akun admin untuk akses menu Pengguna</p>
        </div>
        <div class="card-body login-card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Kata Sandi" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-lock"></span></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember">Ingat saya</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Masuk</button>
                    </div>
                </div>
            </form>
            <div class="mt-3 text-center">
                <a href="{{ route('students.check') }}" class="btn btn-secondary btn-block">Cek Data Siswa Tanpa Login</a>
            </div>
        </div>
    </div>
@endsection
