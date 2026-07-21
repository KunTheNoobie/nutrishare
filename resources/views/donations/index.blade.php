@extends('layouts.app')
@section('title', 'Donations')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-gift"></i> Available Donations</h2>
    @if(Auth::user()->isDonor() || Auth::user()->isAdmin())
    <a href="{{ route('donations.create') }}" class="btn btn-ns-primary">
        <i class="bi bi-plus-circle"></i> Publish Donation
    </a>
    @endif
</div>

<!-- Search -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('donations.index') }}" class="row g-2">
            <div class="col-md-8">
                <input type="text" name="search" class="form-control" placeholder="Search donations..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-ns-primary w-100"><i class="bi bi-search"></i> Search</button>
            </div>
        </form>
    </div>
</div>

<!-- Donations Grid -->
<div class="row g-3">
    @forelse($donations as $donation)
    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    {{-- SECURITY (Module 1): XSS Prevention — all user content escaped --}}
                    <h5 class="card-title">{{ $donation->title }}</h5>
                    <span class="badge bg-{{ $donation->status === 'available' ? 'success' : 'secondary' }}">
                        {{ ucfirst($donation->status) }}
                    </span>
                </div>
                <p class="card-text text-muted">{{ Str::limit($donation->description, 100) }}</p>
                <div class="mb-2">
                    <small><i class="bi bi-box"></i> {{ $donation->quantity }} {{ $donation->unit }}</small><br>
                    <small><i class="bi bi-geo-alt"></i> {{ Str::limit($donation->pickup_address, 40) }}</small><br>
                    <small><i class="bi bi-calendar"></i> Expires: {{ $donation->expiry_date->format('d M Y') }}</small>
                </div>
            </div>
            <div class="card-footer bg-white border-0">
                <a href="{{ route('donations.show', $donation) }}" class="btn btn-outline-primary btn-sm w-100">
                    View Details
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-5 text-muted">
            <i class="bi bi-inbox" style="font-size:3rem;"></i>
            <p class="mt-2">No donations available yet.</p>
        </div>
    </div>
    @endforelse
</div>

<div class="mt-4">{{ $donations->links() }}</div>
@endsection
