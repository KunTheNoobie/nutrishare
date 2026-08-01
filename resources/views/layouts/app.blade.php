<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>NutriShare @hasSection('title')| @yield('title')@endif</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🌾</text></svg>">

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Apply saved theme immediately before render to prevent flicker
        (function() {
            const saved = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-theme', saved);
        })();
    </script>

    <style>
        :root {
            --apple-bg: #000000;
            --apple-surface: #111111;
            --apple-border: #333333;
            --apple-text: #f5f5f7;
            --apple-text-muted: #86868b;
            --apple-accent: #2997ff;
            --apple-accent-hover: #0071e3;
            --apple-danger: #ff3b30;
            --apple-success: #34c759;
            --apple-card-bg: #111111;
            --apple-input-bg: #1a1a1a;
        }

        [data-theme="light"] {
            --apple-bg: #f5f5f7;
            --apple-surface: #ffffff;
            --apple-border: #e5e5ea;
            --apple-text: #1d1d1f;
            --apple-text-muted: #6e6e73;
            --apple-accent: #0066cc;
            --apple-accent-hover: #004499;
            --apple-danger: #ff3b30;
            --apple-success: #28cd41;
            --apple-card-bg: #ffffff;
            --apple-input-bg: #f2f2f7;
        }

        /* Light Mode Text & Component Contrast Fixes */
        [data-theme="light"] .text-light,
        [data-theme="light"] .text-white {
            color: #1d1d1f !important;
        }
        [data-theme="light"] .text-muted {
            color: #6e6e73 !important;
        }
        [data-theme="light"] .table-dark {
            --bs-table-bg: #ffffff !important;
            --bs-table-color: #1d1d1f !important;
            --bs-table-hover-bg: #f2f2f7 !important;
            --bs-table-hover-color: #1d1d1f !important;
            --bs-table-border-color: #e5e5ea !important;
            color: #1d1d1f !important;
            border-color: #e5e5ea !important;
        }
        [data-theme="light"] .table-dark th {
            color: #6e6e73 !important;
            border-bottom-color: #e5e5ea !important;
        }
        [data-theme="light"] .btn-outline-light {
            color: #1d1d1f !important;
            border-color: #c7c7cc !important;
            background-color: transparent !important;
        }
        [data-theme="light"] .btn-outline-light:hover {
            background-color: #e5e5ea !important;
            color: #000000 !important;
        }
        [data-theme="light"] .btn-secondary {
            background-color: #e5e5ea !important;
            color: #1d1d1f !important;
            border-color: #c7c7cc !important;
        }
        [data-theme="light"] .btn-secondary:hover {
            background-color: #d1d1d6 !important;
            color: #000000 !important;
        }
        [data-theme="light"] .bg-dark,
        [data-theme="light"] .bg-secondary {
            background-color: #f2f2f7 !important;
            color: #1d1d1f !important;
        }
        [data-theme="light"] .border-dark,
        [data-theme="light"] .border-secondary {
            border-color: #e5e5ea !important;
        }
        [data-theme="light"] .dropdown-menu {
            background-color: #ffffff !important;
            border-color: #e5e5ea !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
        }
        [data-theme="light"] .dropdown-item {
            color: #1d1d1f !important;
        }
        [data-theme="light"] .dropdown-item:hover {
            background-color: #f2f2f7 !important;
            color: #0066cc !important;
        }
        [data-theme="light"] .list-group-item {
            background-color: #ffffff !important;
            color: #1d1d1f !important;
            border-color: #e5e5ea !important;
        }
        [data-theme="light"] .modal-content {
            background-color: #ffffff !important;
            color: #1d1d1f !important;
            border-color: #e5e5ea !important;
        }
        [data-theme="light"] .datatable-table > tbody > tr:hover {
            background-color: #f2f2f7 !important;
        }
        [data-theme="light"] .datatable-input,
        [data-theme="light"] .datatable-selector {
            background-color: #f2f2f7 !important;
            color: #1d1d1f !important;
            border-color: #e5e5ea !important;
        }
        [data-theme="light"] .btn-check:checked + .btn-outline-light {
            background: rgba(0, 102, 204, 0.1) !important;
            border-color: #0066cc !important;
            color: #0066cc !important;
        }

        /* Global SweetAlert2 Theme Styling */
        .swal2-popup {
            background-color: var(--apple-surface) !important;
            color: var(--apple-text) !important;
            border: 1px solid var(--apple-border) !important;
            border-radius: 20px !important;
            box-shadow: 0 20px 40px rgba(0,0,0,0.5) !important;
        }
        .swal2-title {
            color: var(--apple-text) !important;
            font-family: 'Inter', -apple-system, sans-serif !important;
            font-weight: 600 !important;
        }
        .swal2-html-container, .swal2-content {
            color: var(--apple-text-muted) !important;
            font-family: 'Inter', -apple-system, sans-serif !important;
        }
        .swal2-confirm {
            border-radius: 980px !important;
            padding: 10px 26px !important;
            font-weight: 500 !important;
            font-family: 'Inter', -apple-system, sans-serif !important;
        }
        .swal2-cancel {
            border-radius: 980px !important;
            padding: 10px 26px !important;
            font-weight: 500 !important;
            font-family: 'Inter', -apple-system, sans-serif !important;
        }

        [data-theme="light"] .swal2-popup {
            background-color: #ffffff !important;
            color: #1d1d1f !important;
            border: 1px solid #e5e5ea !important;
            box-shadow: 0 20px 40px rgba(0,0,0,0.12) !important;
        }
        [data-theme="light"] .swal2-title {
            color: #1d1d1f !important;
        }
        [data-theme="light"] .swal2-html-container {
            color: #6e6e73 !important;
        }
        
        body { 
            background-color: var(--apple-bg);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: var(--apple-text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            -webkit-font-smoothing: antialiased;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        /* Apple-style floating blurry navbar */
        .navbar { 
            background: var(--apple-surface) !important;
            backdrop-filter: saturate(180%) blur(20px);
            -webkit-backdrop-filter: saturate(180%) blur(20px);
            border-bottom: 1px solid var(--apple-border);
            padding: 0.75rem 0;
            position: sticky;
            top: 0;
            z-index: 1030;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }
        .navbar-brand { 
            font-weight: 600; 
            font-size: 1.25rem; 
            color: var(--apple-text) !important; 
            letter-spacing: -0.01em;
        }
        .nav-link { 
            font-weight: 400; 
            font-size: 0.85rem;
            color: var(--apple-text-muted) !important; 
            transition: color 0.3s ease; 
        }
        .nav-link:hover { color: var(--apple-text) !important; }

        /* Card & Inputs Light/Dark Overrides */
        .card {
            background-color: var(--apple-card-bg) !important;
            border-color: var(--apple-border) !important;
            color: var(--apple-text) !important;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }
        .card-header {
            background-color: var(--apple-card-bg) !important;
            border-bottom-color: var(--apple-border) !important;
            color: var(--apple-text) !important;
        }
        .form-control, .form-select {
            background-color: var(--apple-input-bg) !important;
            color: var(--apple-text) !important;
            border-color: var(--apple-border) !important;
        }
        .form-control:focus, .form-select:focus {
            background-color: var(--apple-input-bg) !important;
            color: var(--apple-text) !important;
            border-color: var(--apple-accent) !important;
            box-shadow: 0 0 0 0.25rem rgba(41, 151, 255, 0.25) !important;
        }

        /* Nav Tabs High Contrast Styling */
        .nav-tabs {
            border-bottom: 2px solid var(--apple-border) !important;
        }
        .nav-tabs .nav-link {
            color: var(--apple-text-muted) !important;
            border: 1px solid transparent !important;
            border-radius: 8px 8px 0 0 !important;
            font-weight: 500 !important;
            padding: 8px 16px !important;
            transition: all 0.2s ease !important;
        }
        .nav-tabs .nav-link:hover {
            color: var(--apple-text) !important;
            background-color: rgba(125, 125, 125, 0.15) !important;
        }
        .nav-tabs .nav-link.active {
            color: var(--apple-accent) !important;
            background-color: var(--apple-input-bg) !important;
            border-color: var(--apple-border) var(--apple-border) transparent !important;
            border-bottom: 2px solid var(--apple-accent) !important;
            font-weight: 600 !important;
        }
        
        /* Global Button Overrides for System-wide Uniformity */
        .btn {
            border-radius: 980px !important;
            font-weight: 500 !important;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
            padding: 8px 20px !important;
            line-height: 1.4 !important;
            white-space: nowrap !important;
            transition: all 0.2s ease !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 6px !important;
            font-size: 0.875rem !important;
            box-shadow: none !important;
        }
        .btn-sm {
            padding: 6px 16px !important;
            font-size: 0.8rem !important;
            border-radius: 980px !important;
            white-space: nowrap !important;
        }
        .btn-lg {
            padding: 12px 28px !important;
            font-size: 1rem !important;
            border-radius: 980px !important;
            white-space: nowrap !important;
        }
        .btn:hover {
            transform: translateY(-1px) !important;
            opacity: 0.92 !important;
        }
        .btn:active {
            transform: translateY(0) !important;
        }
        .input-group .btn {
            border-radius: 0 980px 980px 0 !important;
            padding-left: 1.25rem !important;
            padding-right: 1.25rem !important;
        }

        /* Custom Button Colors */
        .btn-ns-primary {
            background-color: var(--apple-text);
            color: var(--apple-bg);
            border: none; 
        }
        .btn-ns-primary:hover { 
            background-color: #d1d1d6; 
            color: var(--apple-bg);
        }

        .btn-ns-accent {
            background-color: var(--apple-accent);
            color: white;
            border: none; 
        }
        .btn-ns-accent:hover { 
            background-color: var(--apple-accent-hover); 
            color: white;
        }
        
        /* Forms & Cards */
        .card { 
            background: var(--apple-surface);
            border: 1px solid var(--apple-border); 
            border-radius: 18px; 
            color: var(--apple-text);
        }
        .card-header { 
            background: transparent; 
            border-bottom: 1px solid var(--apple-border);
            border-radius: 18px 18px 0 0 !important; 
            font-weight: 600; 
            padding: 1.5rem;
        }
        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--apple-border);
            color: var(--apple-text);
            border-radius: 12px;
            padding: 0.8rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }
        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--apple-accent);
            color: var(--apple-text);
            box-shadow: 0 0 0 4px rgba(41, 151, 255, 0.2);
        }
        .form-label { color: var(--apple-text-muted); font-weight: 400; font-size: 0.85rem; margin-bottom: 0.5rem; }
        .text-muted { color: var(--apple-text-muted) !important; }
        
        /* Badges */
        .badge { 
            font-weight: 500; 
            padding: 6px 16px; 
            border-radius: 50px; 
            font-size: 0.85rem; 
            display: inline-flex; 
            align-items: center; 
            justify-content: center; 
        }
        .badge-donor { background: rgba(41, 151, 255, 0.15); color: var(--apple-accent); }
        .badge-ngo { background: rgba(52, 199, 89, 0.15); color: var(--apple-success); }
        .badge-admin { background: rgba(255, 59, 48, 0.15); color: var(--apple-danger); }
        .badge-success { background: rgba(52, 199, 89, 0.15); color: var(--apple-success); border: 1px solid rgba(52, 199, 89, 0.3); }
        .badge-warning { background: rgba(255, 159, 10, 0.15); color: #ff9f0a; border: 1px solid rgba(255, 159, 10, 0.3); }
        .badge-secondary { background: rgba(142, 142, 147, 0.15); color: #8e8e93; border: 1px solid rgba(142, 142, 147, 0.3); }
        .badge-moderator { background: rgba(191, 90, 242, 0.15); color: #bf5af2; border: 1px solid rgba(191, 90, 242, 0.3); }
        
        /* Text Utilities */
        .text-apple-accent { color: var(--apple-accent) !important; }
        .text-apple-success { color: var(--apple-success) !important; }
        .text-apple-warning { color: #ff9f0a !important; }
        .text-apple-danger { color: var(--apple-danger) !important; }
        
        /* Form Validation Aesthetics */
        .invalid-feedback { color: var(--apple-danger); font-weight: 400; font-size: 0.8rem; margin-top: 0.5rem; }
        .alert {
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 12px;
            font-size: 0.9rem;
        }
        .alert-danger {
            background: rgba(255, 59, 48, 0.1);
            border: 1px solid rgba(255, 59, 48, 0.2);
            color: #ff453a;
        }
        .alert-success {
            background: rgba(52, 199, 89, 0.1);
            border: 1px solid rgba(52, 199, 89, 0.2);
            color: #32d74b;
        }
        .btn-close { filter: invert(1) grayscale(100%) brightness(200%); opacity: 0.5; }
        .btn-close:hover { opacity: 1; }

        /* Input specific overrides */
        ::placeholder {
            color: var(--apple-text-muted) !important;
            opacity: 0.8 !important;
        }
        select option {
            background-color: var(--apple-surface);
            color: var(--apple-text);
        }
        input[type="file"]::file-selector-button {
            background-color: var(--apple-text);
            color: var(--apple-bg);
            border: none;
            border-radius: 6px;
            padding: 0.5rem 1rem;
            margin-right: 1rem;
            font-weight: 500;
            font-family: inherit;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        input[type="file"]::file-selector-button:hover {
            background-color: #d1d1d6;
        }
        
        .dropdown-item:hover {
            background-color: rgba(255, 255, 255, 0.05);
            color: var(--apple-text);
        }

        .form-text { color: #a1a1aa !important; }

        /* Animations */
        .animate-slide-up {
            animation: slideUpFade 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
            transform: translateY(20px);
        }
        @keyframes slideUpFade {
            to { transform: translateY(0); opacity: 1; }
        }

        main { flex-grow: 1; display: flex; flex-direction: column; }
        footer { background: transparent; color: var(--apple-text-muted); border-top: 1px solid var(--apple-border); font-size: 0.8rem; }
        
        a { color: var(--apple-accent); text-decoration: none; transition: opacity 0.2s; }
        a:hover { color: var(--apple-accent); opacity: 0.8; }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3/dist/style.css" rel="stylesheet">
    <style>
        /* Custom Simple-DataTables Dark & Light Theme Override */
        .datatable-wrapper { background: transparent !important; color: var(--apple-text) !important; }
        .datatable-container { background: transparent !important; border: none !important; }
        .datatable-table { background: transparent !important; color: var(--apple-text) !important; border-collapse: separate; border-spacing: 0; }
        .datatable-table > thead > tr > th { background: var(--apple-surface) !important; border-bottom: 1px solid var(--apple-border) !important; color: var(--apple-text) !important; font-weight: 600; }
        .datatable-table > tbody > tr > td { background: transparent !important; border-bottom: 1px solid var(--apple-border) !important; color: var(--apple-text) !important; vertical-align: middle; }
        .datatable-table > tbody > tr:hover { background-color: var(--apple-input-bg) !important; }
        .datatable-input { background: var(--apple-input-bg) !important; border: 1px solid var(--apple-border) !important; color: var(--apple-text) !important; border-radius: 8px; padding: 0.375rem 0.75rem; }
        .datatable-selector { background: var(--apple-input-bg) !important; border: 1px solid var(--apple-border) !important; color: var(--apple-text) !important; border-radius: 8px; padding: 0.375rem 1.75rem 0.375rem 0.75rem; }
        .datatable-pagination a { color: var(--apple-accent) !important; background: transparent !important; border: 1px solid transparent !important; border-radius: 8px; }
        .datatable-pagination a:hover { background: var(--apple-surface) !important; border-color: var(--apple-border) !important; }
        .datatable-pagination .active a { background: var(--apple-accent) !important; color: #ffffff !important; }
        .datatable-sorter::before, .datatable-sorter::after { opacity: 0.4; }

        [data-theme="light"] .datatable-table > thead > tr > th { background: #f2f2f7 !important; color: #1d1d1f !important; }
        [data-theme="light"] .datatable-table > tbody > tr > td { color: #1d1d1f !important; }
        [data-theme="light"] .datatable-input,
        [data-theme="light"] .datatable-selector { background: #ffffff !important; color: #1d1d1f !important; border-color: #e5e5ea !important; }
    </style>
    @stack('styles')
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg py-3">
    <div class="container-fluid px-4">
        <a class="navbar-brand" href="{{ route('home') }}">🌾 NutriShare</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('donations.index') }}"><i class="bi bi-basket"></i> Donations</a>
                    </li>

                    @if(Auth::user()->isNgo() || Auth::user()->isAdmin() || Auth::user()->isModerator())
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('inventory.index') }}"><i class="bi bi-box-seam"></i> Inventory</a>
                    </li>
                    @endif

                    @if(Auth::user()->isAdmin() || Auth::user()->isModerator())
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('reports.index') }}"><i class="bi bi-graph-up"></i> Reports</a>
                    </li>
                    @endif
                @endauth
            </ul>
            <ul class="navbar-nav align-items-center gap-2">
                <li class="nav-item me-1">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle p-2 d-flex align-items-center justify-content-center" id="themeToggleBtn" title="Toggle Light / Dark Mode" onclick="toggleTheme()" style="width: 38px; height: 38px;">
                        <i id="themeIcon" class="bi bi-moon-stars-fill text-info fs-6"></i>
                    </button>
                </li>
                @guest
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="btn btn-ns-primary ms-2" href="{{ route('register') }}">Register</a></li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">
                            <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="rounded-circle border border-dark" style="width: 28px; height: 28px; object-fit: cover;">
                            {{ Auth::user()->name }}
                        @if(Auth::user()->isDonor())
                            <span class="badge badge-success float-end ms-2" style="margin-top: 2px;">Donor</span>
                        @elseif(Auth::user()->isNgo())
                            <span class="badge badge-warning float-end ms-2" style="margin-top: 2px;">NGO</span>
                        @elseif(Auth::user()->isAdmin())
                            <span class="badge badge-primary float-end ms-2" style="margin-top: 2px;">Admin</span>
                        @elseif(Auth::user()->isModerator())
                            <span class="badge badge-moderator float-end ms-2" style="margin-top: 2px;">Moderator</span>
                        @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-dark shadow" style="background-color: var(--apple-surface);">
                            <li><a class="dropdown-item" href="{{ route('dashboard') }}" style="color: var(--apple-text);"><i class="bi bi-speedometer2 text-muted me-2"></i> Dashboard</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}" style="color: var(--apple-text);"><i class="bi bi-person-gear text-muted me-2"></i> Profile Settings</a></li>
                            <li><hr class="dropdown-divider border-dark"></li>
                            <li>
                                {{-- SECURITY (Module 3): CSRF token on logout form --}}
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i> Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<!-- Flash Messages -->
<div class="container-fluid px-4 mt-3">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    {{-- SECURITY (Module 1): XSS Prevention — {{ }} auto-escapes error messages --}}
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>

<!-- Main Content -->
<main class="container-fluid px-4 py-4">
    @yield('content')
</main>

<!-- Footer -->
<footer class="py-4 mt-5">
    <div class="container text-center">
        <p class="mb-1 text-muted" style="font-size: 0.95rem;">🌾 <strong>NutriShare</strong> — Surplus Food Redistribution Platform</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Theme Switcher Logic
function toggleTheme() {
    const current = document.documentElement.getAttribute('data-theme') || 'dark';
    const next = current === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', next);
    localStorage.setItem('theme', next);
    updateThemeIcon(next);
}

function updateThemeIcon(theme) {
    const icon = document.getElementById('themeIcon');
    if (icon) {
        if (theme === 'light') {
            icon.className = 'bi bi-sun-fill text-warning fs-6';
        } else {
            icon.className = 'bi bi-moon-stars-fill text-info fs-6';
        }
    }
}

// Update icon on page load
document.addEventListener('DOMContentLoaded', function() {
    const saved = localStorage.getItem('theme') || 'dark';
    updateThemeIcon(saved);
});

// Password field toggle
document.addEventListener('click', function(e) {
    if (e.target.closest('.toggle-password')) {
        const btn = e.target.closest('.toggle-password');
        const input = btn.previousElementSibling;
        const icon = btn.querySelector('i');
        if (input && input.tagName === 'INPUT') {
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        }
    }
});

// GLOBAL SWEETALERT2 POPUP INTERCEPTOR
// Replaces native browser 127.0.0.1 says "Are you sure?" popups everywhere!
document.addEventListener('click', function(e) {
    const target = e.target.closest('[onclick*="confirm"]');
    if (target) {
        e.preventDefault();
        e.stopPropagation();

        const onclickVal = target.getAttribute('onclick');
        const match = onclickVal.match(/confirm\(['"](.+?)['"]\)/);
        const message = match ? match[1] : 'Are you sure you want to proceed?';
        const form = target.closest('form');

        const isDark = (document.documentElement.getAttribute('data-theme') || 'dark') === 'dark';

        Swal.fire({
            title: 'Confirmation',
            text: message,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ff3b30',
            cancelButtonColor: '#8e8e93',
            confirmButtonText: 'Yes, proceed',
            cancelButtonText: 'Cancel',
            background: isDark ? '#1c1c1e' : '#ffffff',
            color: isDark ? '#ffffff' : '#1d1d1f',
            customClass: {
                popup: 'rounded-4 shadow-lg border border-secondary'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                target.removeAttribute('onclick');
                if (form) {
                    form.submit();
                } else if (target.tagName === 'A' && target.href) {
                    window.location.href = target.href;
                }
            }
        });
        return false;
    }
}, true);
</script>
@stack('scripts')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize all tables with class 'datatable'
        const dataTables = document.querySelectorAll(".datatable");
        dataTables.forEach(table => {
            new simpleDatatables.DataTable(table, {
                searchable: true,
                fixedHeight: true,
                perPage: 10
            });
        });
    });
</script>
</body>
</html>
