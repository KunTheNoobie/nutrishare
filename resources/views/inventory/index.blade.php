@extends('layouts.app')
@section('title', 'Inventory Locations')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-box-seam"></i> Inventory Locations</h2>
    <a href="{{ route('inventory.create') }}" class="btn btn-ns-primary">
        <i class="bi bi-plus-circle"></i> Add Location
    </a>
</div>

<div class="row g-3">
    @forelse($locations as $location)
    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <h5>{{ $location->name }}</h5>
                <p class="text-muted mb-2">{{ Str::limit($location->address, 50) }}</p>
                <span class="badge bg-{{ $location->storage_type === 'cold' ? 'info' : ($location->storage_type === 'frozen' ? 'primary' : 'success') }}">
                    {{ ucfirst($location->storage_type) }} Storage
                </span>
                @if($location->capacity)
                <div class="mt-2">
                    <small>Capacity: {{ $location->current_occupancy }}/{{ $location->capacity }} kg</small>
                    <div class="progress" style="height:6px;">
                        <div class="progress-bar" style="width:{{ ($location->current_occupancy / max($location->capacity, 1)) * 100 }}%"></div>
                    </div>
                </div>
                @endif
                <p class="mt-2 mb-0"><small><i class="bi bi-list"></i> {{ $location->food_items_count }} items</small></p>
            </div>
            <div class="card-footer bg-white">
                <a href="{{ route('inventory.show', $location) }}" class="btn btn-outline-primary btn-sm w-100">Manage</a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5 text-muted">
        <i class="bi bi-box-seam" style="font-size:3rem;"></i>
        <p class="mt-2">No inventory locations yet. <a href="{{ route('inventory.create') }}">Add your first one!</a></p>
    </div>
    @endforelse
</div>

<div class="mt-4">{{ $locations->links() }}</div>
@endsection
