@extends('layouts.app')
@section('title', 'Browse Donations')

@section('content')
<h2 class="mb-4"><i class="bi bi-search"></i> Browse Available Donations</h2>

<div class="row g-3">
    @forelse($donations as $donation)
    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <h5>{{ $donation->title }}</h5>
                <p class="text-muted">{{ Str::limit($donation->description, 80) }}</p>
                <p class="mb-1"><i class="bi bi-box"></i> {{ $donation->quantity }} {{ $donation->unit }}</p>
                <p class="mb-1"><i class="bi bi-geo-alt"></i> {{ Str::limit($donation->pickup_address, 35) }}</p>
                <p class="mb-1"><i class="bi bi-calendar"></i> Expires: {{ $donation->expiry_date->format('d M Y') }}</p>
                <p class="mb-0"><i class="bi bi-person"></i> {{ $donation->donor->name }}</p>
            </div>
            <div class="card-footer bg-white">
                <a href="{{ route('donations.show', $donation) }}" class="btn btn-ns-primary btn-sm w-100">
                    <i class="bi bi-hand-thumbs-up"></i> View & Claim
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5 text-muted">
        <i class="bi bi-inbox" style="font-size:3rem;"></i>
        <p class="mt-2">No donations available right now. Check back soon!</p>
    </div>
    @endforelse
</div>

<div class="mt-4">{{ $donations->links() }}</div>
@endsection
