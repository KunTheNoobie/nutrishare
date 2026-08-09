@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-5 col-lg-4">
        <div class="card shadow-sm animate-slide-up">
            <div class="card-header text-center">
                <h4 class="mb-0"><i class="bi bi-box-arrow-in-right"></i> Login</h4>
            </div>
            <div class="card-body p-4">
                {{-- SECURITY (Module 3): @csrf prevents CSRF attacks --}}
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control @error('password') is-invalid @enderror @error('email') is-invalid @enderror"
                                   id="password" name="password" required>
                            <button class="btn btn-outline-secondary toggle-password" type="button" style="border: 1px solid var(--apple-border);">
                                <i class="bi bi-eye"></i>
                            </button>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4 form-check d-flex justify-content-between align-items-center">
                        <div>
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label text-muted" style="font-size: 0.9rem;" for="remember">Remember me</label>
                        </div>
                        <a href="{{ route('password.request') }}" style="font-size: 0.85rem;">Forgot Password?</a>
                    </div>

                    <button type="submit" class="btn btn-ns-primary w-100 mb-3">
                        <i class="bi bi-box-arrow-in-right"></i> Sign In
                    </button>
                </form>

                <div class="text-center mt-3 mb-3">
                    <span class="text-muted" style="font-size: 0.9rem;">Don't have an account?</span>
                    <a href="{{ route('register') }}" style="font-size: 0.9rem; font-weight: 500;">Create one now</a>
                </div>

                <!-- Presentation Quick Login Buttons -->
                <div class="pt-3 border-top text-center" style="border-color: var(--apple-border) !important;">
                    <small class="text-muted d-block mb-2 fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.5px;">🎭 PRESENTATION QUICK DEMO LOGIN</small>
                    <div class="d-flex flex-wrap justify-content-center gap-1">
                        <a href="{{ route('demo.login', 'admin') }}" class="btn btn-outline-danger btn-sm py-1 px-2" style="font-size: 0.75rem !important;">
                            <i class="bi bi-shield-lock"></i> Admin
                        </a>
                        <a href="{{ route('demo.login', 'moderator') }}" class="btn btn-outline-info btn-sm py-1 px-2" style="font-size: 0.75rem !important;">
                            <i class="bi bi-shield-check"></i> Moderator
                        </a>
                        <a href="{{ route('demo.login', 'ngo') }}" class="btn btn-outline-success btn-sm py-1 px-2" style="font-size: 0.75rem !important;">
                            <i class="bi bi-building-heart"></i> NGO
                        </a>
                        <a href="{{ route('demo.login', 'donor') }}" class="btn btn-outline-primary btn-sm py-1 px-2" style="font-size: 0.75rem !important;">
                            <i class="bi bi-shop"></i> Donor
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
