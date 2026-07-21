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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
        }
        
        body { 
            background-color: var(--apple-bg);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: var(--apple-text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            -webkit-font-smoothing: antialiased;
        }
        
        /* Apple-style floating blurry navbar */
        .navbar { 
            background: rgba(0, 0, 0, 0.72) !important;
            backdrop-filter: saturate(180%) blur(20px);
            -webkit-backdrop-filter: saturate(180%) blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 0.75rem 0;
            position: sticky;
            top: 0;
            z-index: 1030;
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
        
        /* Buttons */
        .btn-ns-primary {
            background-color: var(--apple-text);
            color: var(--apple-bg);
            border: none; 
            border-radius: 980px; 
            padding: 8px 24px; 
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        .btn-ns-primary:hover { 
            background-color: #d1d1d6; 
            color: var(--apple-bg);
            transform: scale(1.02);
        }

        .btn-ns-accent {
            background-color: var(--apple-accent);
            color: white;
            border: none; 
            border-radius: 980px; 
            padding: 10px 24px; 
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.3s ease;
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
        .badge { font-weight: 500; padding: 0.4em 0.8em; border-radius: 6px; }
        .badge-donor { background: rgba(41, 151, 255, 0.15); color: var(--apple-accent); }
        .badge-ngo { background: rgba(52, 199, 89, 0.15); color: var(--apple-success); }
        .badge-admin { background: rgba(255, 59, 48, 0.15); color: var(--apple-danger); }
        
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
