@extends('layouts.app')
@section('title', '403 - Forbidden Access')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-7 col-lg-6 text-center py-5">
        <div class="card p-5 shadow-sm animate-slide-up" style="border-radius: 18px;">
            <div class="mb-4">
                <span class="display-1 fw-bold text-apple-danger">403</span>
                <i class="bi bi-shield-lock-fill d-block fs-1 text-apple-danger mt-2 opacity-75"></i>
            </div>
            <h3 class="fw-bold mb-2" style="color: var(--apple-text);">Access Forbidden</h3>
            <p class="text-muted mb-4" style="font-size: 0.95rem;">
                {{ $exception->getMessage() ?: 'You do not have the required permissions or role access to perform this operation.' }}
            </p>
            <div class="d-flex justify-content-center gap-2 flex-wrap">
                <a href="{{ route('dashboard') }}" class="btn btn-ns-primary px-4">
                    <i class="bi bi-speedometer2 me-1"></i> Return to Dashboard
                </a>
                <a href="{{ route('donations.index') }}" class="btn btn-outline-secondary px-4" style="border-color: var(--apple-border); color: var(--apple-text);">
                    <i class="bi bi-basket me-1"></i> Browse Donations
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
