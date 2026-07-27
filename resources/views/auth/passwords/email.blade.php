@extends('layouts.app')
@section('title', 'Reset Password')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-5 col-lg-4">
        <div class="card shadow-sm animate-slide-up">
            <div class="card-header text-center">
                <h4 class="mb-0">Reset Password</h4>
            </div>
            <div class="card-body p-4">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                        @if(session('reset_url'))
                            <hr style="border-color: rgba(52, 199, 89, 0.3);">
                            <strong class="text-light">Local Dev Mode:</strong> <a href="{{ session('reset_url') }}" class="text-white text-decoration-underline" style="font-weight: 500;">Click here to instantly reset your password</a>
                        @endif
                    </div>
                @else
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-ns-primary w-100 mb-3">
                        Send Password Reset Link
                    </button>
                </form>
                @endif

                <div class="text-center mt-3">
                    <a href="{{ route('login') }}" class="text-muted" style="font-size: 0.9rem;">Back to Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
