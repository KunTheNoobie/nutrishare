@extends('layouts.app')
@section('title', 'Inventory Locations')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 animate-slide-up">
    <h2><i class="bi bi-box-seam text-apple-accent"></i> Inventory Locations</h2>
    <a href="{{ route('inventory.create') }}" class="btn btn-ns-primary">
        <i class="bi bi-plus-circle"></i> Add Location
    </a>
</div>

<div class="row g-3 animate-slide-up" style="animation-delay: 0.1s;">
    @forelse($locations as $location)
    <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 style="font-weight: 600;">{{ $location->name }}</h5>
                <p class="text-muted mb-3" style="font-size: 0.95rem;">{{ Str::limit($location->address, 50) }}</p>
                <span class="badge badge-{{ $location->storage_type === 'cold' ? 'donor' : ($location->storage_type === 'frozen' ? 'admin' : 'success') }}">
                    {{ ucfirst($location->storage_type) }} Storage
                </span>
                @if($location->capacity)
                <div class="mt-3">
                    <small class="text-muted">Capacity: {{ $location->current_occupancy }}/{{ $location->capacity }} kg</small>
                    <div class="progress mt-1" style="height:6px; background-color: var(--apple-border);">
                        <div class="progress-bar bg-primary" style="width:{{ ($location->current_occupancy / max($location->capacity, 1)) * 100 }}%; background-color: var(--apple-accent) !important;"></div>
                    </div>
                </div>
                @endif
                <p class="mt-3 mb-0" style="color: #a1a1aa;"><small><i class="bi bi-list text-apple-accent"></i> {{ $location->food_items_count }} items</small></p>
            </div>
            <div class="card-footer bg-transparent border-top-0 pt-0">
                <a href="{{ route('inventory.show', $location) }}" class="btn btn-ns-primary w-100" style="background-color: var(--apple-surface); border: 1px solid var(--apple-border); color: var(--apple-text);">Manage</a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <i class="bi bi-box-seam text-muted" style="font-size:4rem; opacity: 0.5;"></i>
        <p class="mt-3 text-muted" style="font-size: 1.1rem;">No inventory locations yet. <a href="{{ route('inventory.create') }}">Add your first one!</a></p>
    </div>
    @endforelse
</div>

<div class="mt-4 animate-slide-up" style="animation-delay: 0.2s;">{{ $locations->links() }}</div>
@endsection
