@extends('layouts.app')
@section('title', 'Browse Donations')

@section('content')
<h2 class="mb-4 animate-slide-up"><i class="bi bi-search text-apple-accent"></i> Browse Available Donations</h2>

<div class="row g-3 animate-slide-up" style="animation-delay: 0.1s;">
    @forelse($donations as $donation)
    <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 style="font-weight: 600;">{{ $donation->title }}</h5>
                <p class="text-muted mb-3" style="font-size: 0.95rem;">{{ Str::limit($donation->description, 80) }}</p>
                <div style="font-size: 0.85rem; color: #a1a1aa;">
                    <p class="mb-1"><i class="bi bi-box text-apple-accent"></i> {{ $donation->quantity }} {{ $donation->unit }}</p>
                    <p class="mb-1"><i class="bi bi-geo-alt text-apple-accent"></i> {{ Str::limit($donation->pickup_address, 35) }}</p>
                    <p class="mb-1"><i class="bi bi-calendar text-apple-accent"></i> Expires: {{ $donation->expiry_date->format('d M Y') }}</p>
                    <p class="mb-0"><i class="bi bi-person text-apple-accent"></i> {{ $donation->donor->name }}</p>
                </div>
            </div>
            <div class="card-footer bg-transparent border-top-0 pt-0">
                <a href="{{ route('donations.show', $donation) }}" class="btn btn-ns-primary w-100" style="background-color: var(--apple-surface); border: 1px solid var(--apple-border); color: var(--apple-text);">
                    <i class="bi bi-hand-thumbs-up text-apple-accent"></i> View & Claim
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <i class="bi bi-inbox text-muted" style="font-size:4rem; opacity: 0.5;"></i>
        <p class="mt-3 text-muted" style="font-size: 1.1rem;">No donations available right now. Check back soon!</p>
    </div>
    @endforelse
</div>

<div class="mt-4 animate-slide-up" style="animation-delay: 0.2s;">{{ $donations->links() }}</div>
@endsection
