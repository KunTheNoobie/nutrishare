@extends('layouts.app')
@section('title', 'Donations')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 animate-slide-up">
    <h2><i class="bi bi-gift text-apple-accent"></i> Available Donations</h2>
    @if(Auth::user()->isDonor() || Auth::user()->isAdmin())
    <a href="{{ route('donations.create') }}" class="btn btn-ns-primary">
        <i class="bi bi-plus-circle"></i> Publish Donation
    </a>
    @endif
</div>

<!-- Search -->
<div class="card shadow-sm mb-4 animate-slide-up">
    <div class="card-body p-2">
        <form method="GET" action="{{ route('donations.index') }}" class="row g-2 align-items-center m-0">
            <div class="col-md-9">
                <input type="text" name="search" class="form-control border-0 bg-transparent" placeholder="Search donations..."
                       value="{{ request('search') }}" style="box-shadow: none;">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-ns-primary w-100" style="background-color: var(--apple-surface); border: 1px solid var(--apple-border); color: var(--apple-text);">
                    <i class="bi bi-search text-apple-accent"></i> Search
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Donations Grid -->
<div class="row g-3 animate-slide-up" style="animation-delay: 0.1s;">
    @forelse($donations as $donation)
    <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    {{-- SECURITY (Module 1): XSS Prevention — all user content escaped --}}
                    <h5 class="card-title mb-0" style="font-weight: 600;">{{ $donation->title }}</h5>
                    <span class="badge badge-{{ $donation->status === 'available' ? 'success' : 'secondary' }}">
                        {{ ucfirst($donation->status) }}
                    </span>
                </div>
                <p class="card-text text-muted mb-3" style="font-size: 0.95rem;">{{ Str::limit($donation->description, 100) }}</p>
                <div class="mb-2" style="font-size: 0.85rem; color: #a1a1aa;">
                    <div class="mb-1"><i class="bi bi-box text-apple-accent"></i> {{ $donation->quantity }} {{ $donation->unit }}</div>
                    <div class="mb-1"><i class="bi bi-geo-alt text-apple-accent"></i> {{ Str::limit($donation->pickup_address, 40) }}</div>
                    <div><i class="bi bi-calendar text-apple-accent"></i> Expires: {{ $donation->expiry_date->format('d M Y') }}</div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-top-0 pt-0">
                <a href="{{ route('donations.show', $donation) }}" class="btn btn-ns-primary w-100" style="background-color: var(--apple-surface); border: 1px solid var(--apple-border); color: var(--apple-text);">
                    View Details
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-5">
            <i class="bi bi-inbox text-muted" style="font-size:4rem; opacity: 0.5;"></i>
            <p class="mt-3 text-muted" style="font-size: 1.1rem;">No donations available yet.</p>
        </div>
    </div>
    @endforelse
</div>

<div class="mt-4">{{ $donations->links() }}</div>
@endsection
