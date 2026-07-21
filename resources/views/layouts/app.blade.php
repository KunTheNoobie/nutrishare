<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'NutriShare') — Surplus Food Redistribution Platform</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --ns-primary: #10b981;
            --ns-primary-dark: #059669;
            --ns-dark: #0f172a;
            --ns-darker: #020617;
            --ns-surface: rgba(30, 41, 59, 0.7);
        }
        body { 
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            font-family: 'Inter', sans-serif;
            color: #e2e8f0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .navbar { 
            background: rgba(15, 23, 42, 0.8) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1rem 0;
        }
        .navbar-brand { font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 1.5rem; color: #10b981 !important; }
        .nav-link { font-weight: 500; color: #cbd5e1 !important; transition: all 0.2s; }
        .nav-link:hover { color: #10b981 !important; transform: translateY(-1px); }
        .btn-ns-primary {
            background: linear-gradient(135deg, var(--ns-primary), var(--ns-primary-dark));
            color: white; border: none; border-radius: 8px; padding: 8px 24px; font-weight: 600;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            transition: all 0.3s ease;
        }
        .btn-ns-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4); color: white; }
        .card { 
            background: var(--ns-surface);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1); 
            border-radius: 16px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.2); 
            color: white;
        }
        .card-header { 
            background: rgba(255, 255, 255, 0.05); 
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px 16px 0 0 !important; 
            font-weight: 600; 
            padding: 1.25rem;
        }
        .form-control, .form-select {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            border-radius: 8px;
            padding: 0.75rem 1rem;
        }
        .form-control:focus, .form-select:focus {
            background: rgba(15, 23, 42, 0.8);
            border-color: var(--ns-primary);
            color: white;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.25);
        }
        .form-label { color: #cbd5e1; font-weight: 500; }
        .text-muted { color: #94a3b8 !important; }
        .badge-donor { background: rgba(59, 130, 246, 0.2); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3); }
        .badge-ngo { background: rgba(168, 85, 247, 0.2); color: #c084fc; border: 1px solid rgba(168, 85, 247, 0.3); }
        .badge-admin { background: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); }
        
        main { flex-grow: 1; }
        footer { background: transparent; color: #64748b; border-top: 1px solid rgba(255, 255, 255, 0.05); }
    </style>
    @stack('styles')
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark py-2">
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
                        <a class="nav-link" href="{{ route('donations.index') }}"><i class="bi bi-gift"></i> Donations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('claims.browse') }}"><i class="bi bi-hand-thumbs-up"></i> Browse & Claim</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('inventory.index') }}"><i class="bi bi-box-seam"></i> Inventory</a>
                    </li>
                @endauth
            </ul>
            <ul class="navbar-nav">
                @guest
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="btn btn-ns-primary ms-2" href="{{ route('register') }}">Register</a></li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i>
                            {{-- SECURITY (Module 1): XSS Prevention — {{ }} auto-escapes output --}}
                            {{ Auth::user()->name }}
                            <span class="badge bg-{{ Auth::user()->role === 'admin' ? 'danger' : (Auth::user()->role === 'ngo' ? 'purple' : 'primary') }} ms-1">
                                {{ ucfirst(Auth::user()->role) }}
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                {{-- SECURITY (Module 3): CSRF token on logout form --}}
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right"></i> Logout</button>
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
        <p class="mb-1">🌾 <strong>NutriShare</strong> — Surplus Food Redistribution Platform</p>
        <p class="mb-0 small">SDG 2: Zero Hunger | BMIT3173 Integrative Programming</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
