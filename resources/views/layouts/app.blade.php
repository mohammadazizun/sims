<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIMS - Sistem Informasi Manajemen Siswa')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .brand-link { background-color: #343a40; color: #ffffff; }
        .content-wrapper { min-height: calc(100vh - 57px); background: #f4f6f9; }
        .login-page { background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%); }
        .login-card-body { padding: 20px; }

        .login-box { width: 360px; margin: 8% auto; }
        .login-logo a { color: #ffffff; font-size: 2rem; }
        .login-logo a b { color: #ffd54f; }

        .form-actions { display: flex; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 1rem; }
        .button,
        .button.button-secondary,
        .button.button-danger,
        .btn-custom {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.55rem 1rem;
            border-radius: 0.375rem;
            border: 1px solid transparent;
            text-decoration: none;
            color: #fff;
            cursor: pointer;
        }
        .button { background-color: #007bff; }
        .button.button-secondary { background-color: #6c757d; }
        .button.button-danger { background-color: #dc3545; }
        .button:hover,
        .btn-custom:hover { opacity: 0.92; }

        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        table th,
        table td { border: 1px solid #dee2e6; padding: 0.75rem; vertical-align: middle; }
        table thead th { background: #f8f9fa; }
        table tbody tr:hover { background: rgba(0, 0, 0, 0.03); }

        .card h2,
        .card h3,
        .card h4,
        .card h5,
        .card p { margin: 0; }

        .card { box-shadow: 0 0.35rem 0.75rem rgba(0, 0, 0, 0.05); }
        .card .card-body { padding: 1.25rem; }

        .content-header .container-fluid { padding-top: 1rem; padding-bottom: 0.5rem; }

        .table-responsive { overflow-x: auto; }
        .breadcrumb { background: transparent; margin-bottom: 0; padding: 0; }
    </style>
</head>
<body class="hold-transition {{ auth()->check() ? 'sidebar-mini layout-fixed' : (request()->routeIs('login') ? 'login-page' : '') }}">
@if(auth()->check())
<div class="wrapper">
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item d-none d-sm-inline-block">
                <span class="nav-link">{{ auth()->user()->name }}</span>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-secondary">Logout</button>
                </form>
            </li>
        </ul>
    </nav>

    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="{{ route('students.index') }}" class="brand-link">
            <span class="brand-text font-weight-light">SIMS Admin</span>
        </a>
        <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="info">
                    <a href="#" class="d-block">{{ auth()->user()->name }}</a>
                </div>
            </div>
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="{{ route('students.index') }}" class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-graduate"></i>
                            <p>Siswa</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('classrooms.index') }}" class="nav-link {{ request()->routeIs('classrooms.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chalkboard"></i>
                            <p>Kelas</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('dapodik.sync.form') }}" class="nav-link {{ request()->is('dapodik*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-sync-alt"></i>
                            <p>Dapodik</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('reports.students') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p>Laporan</p>
                        </a>
                    </li>
                    @if(auth()->user()->is_admin)
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Pengguna</p>
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    </aside>

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>@yield('title', 'Dashboard')</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @yield('content')
            </div>
        </section>
    </div>

    <footer class="main-footer">
        <div class="float-end d-none d-sm-inline">SIMS v1.0</div>
        <strong>Copyright &copy; 2026.</strong> All rights reserved.
    </footer>
</div>
@elseif(request()->routeIs('login'))
    <div class="login-box">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @yield('content')
    </div>
@else
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content">
                <div class="container-fluid py-4">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @yield('content')
                </div>
            </section>
        </div>
        <footer class="main-footer">
            <div class="float-end d-none d-sm-inline">SIMS v1.0</div>
            <strong>Copyright &copy; 2026.</strong> All rights reserved.
        </footer>
    </div>
@endif

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>
</html>
