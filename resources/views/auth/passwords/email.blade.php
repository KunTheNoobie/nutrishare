@extends('layouts.app')
@section('title', 'Reset Password')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm animate-slide-up">
            <div class="card-header text-center">
                <h4 class="mb-0"><i class="bi bi-shield-lock"></i> Reset Password</h4>
                <div class="text-muted small mt-1">Step 1 of 3: Enter Your Email</div>
            </div>
            <div class="card-body p-4">
                <p class="text-center text-muted small mb-4">
                    Enter your registered email address and we'll send you a <strong>6-digit OTP code</strong> to verify your identity.
                </p>

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        <i class="bi bi-check-circle me-1"></i> {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="form-label"><i class="bi bi-envelope me-1"></i> Email Address</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email') }}" required autofocus
                               placeholder="you@example.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-ns-primary w-100 mb-3 py-2 fw-semibold">
                        <i class="bi bi-send me-1"></i> Send OTP Code
                    </button>
                </form>

                <div class="text-center mt-3">
                    <a href="{{ route('login') }}" class="text-muted" style="font-size: 0.9rem;">
                        <i class="bi bi-arrow-left me-1"></i> Back to Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
