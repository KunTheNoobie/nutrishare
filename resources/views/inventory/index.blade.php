@extends('layouts.app')
@section('title', 'Inventory Locations')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 animate-slide-up">
    <div>
        <h2 style="font-weight: 600;" class="mb-1"><i class="bi bi-box-seam text-apple-accent"></i> Inventory Locations</h2>
        <p class="mb-0" style="color: var(--apple-text-muted);">Manage food storage facilities, capacity, and inventory safety audits.</p>
    </div>
    @if(Auth::user()->isNgo())
    <a href="{{ route('inventory.create') }}" class="btn btn-ns-primary">
        <i class="bi bi-plus-circle"></i> Add Location
    </a>
    @endif
</div>

<div class="card shadow-sm animate-slide-up">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-geo-alt text-apple-accent me-1"></i> Storage Facilities List</span>
        <span class="badge border px-3 py-1" style="background-color: var(--apple-input-bg); color: var(--apple-text); border-color: var(--apple-border) !important; font-size: 0.75rem; font-weight: 500;">
            {{ $locations->total() }} Total Facilities
        </span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr class="border-bottom" style="border-color: var(--apple-border) !important;">
                        <th class="ps-4 py-3 fw-semibold small" style="color: var(--apple-text-muted);">Location Name</th>
                        <th class="py-3 fw-semibold small" style="color: var(--apple-text-muted);">Storage Type</th>
                        <th class="py-3 fw-semibold small" style="color: var(--apple-text-muted);">Managing Organization</th>
                        <th class="py-3 fw-semibold small" style="color: var(--apple-text-muted);">Capacity / Items</th>
                        <th class="pe-4 py-3 fw-semibold small text-end" style="color: var(--apple-text-muted);">Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($locations as $location)
                <tr class="border-bottom" style="border-color: var(--apple-border) !important;">
                    <td class="ps-4">
                        <strong style="color: var(--apple-text);">
                            <a href="{{ route('inventory.show', $location) }}" class="text-decoration-none" style="color: var(--apple-text);">{{ $location->name }}</a>
                        </strong>
                        <br><small style="color: var(--apple-text-muted); font-size: 0.78rem;"><i class="bi bi-geo-alt me-1"></i>{{ Str::limit($location->address, 45) }}</small>
                    </td>
                    <td>
                        <span class="badge badge-{{ $location->storage_type === 'cold' ? 'donor' : ($location->storage_type === 'frozen' ? 'admin' : 'success') }}">
                            {{ ucfirst($location->storage_type) }} Storage
                        </span>
                    </td>
                    <td>
                        @if($location->user)
                        <span class="action-tag">
                            <i class="bi bi-building me-1"></i>{{ $location->user->organization_name ?? $location->user->name }}
                        </span>
                        @else
                        <span style="color: var(--apple-text-muted);">—</span>
                        @endif
                    </td>
                    <td>
                        <strong style="color: var(--apple-text);">{{ $location->food_items_count }} Items</strong>
                        @if($location->capacity)
                        <div class="mt-1" style="max-width: 140px;">
                            <div class="progress" style="height: 5px; background-color: var(--apple-border);">
                                <div class="progress-bar" style="width: {{ min(($location->current_occupancy / max($location->capacity, 1)) * 100, 100) }}%; background-color: var(--apple-accent) !important;"></div>
                            </div>
                            <small style="font-size: 0.72rem; color: var(--apple-text-muted);">{{ number_format($location->current_occupancy, 1) }}/{{ number_format($location->capacity, 1) }} kg</small>
                        </div>
                        @endif
                    </td>
                    <td class="pe-4 text-end">
                        <a href="{{ route('inventory.show', $location) }}" class="btn btn-sm btn-outline-light text-nowrap">
                            @if(Auth::user()->isNgo() && $location->user_id === Auth::id())
                                <i class="bi bi-gear me-1"></i> Manage Pantry
                            @else
                                <i class="bi bi-shield-check me-1"></i> Audit Inventory
                            @endif
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5" style="color: var(--apple-text-muted);">
                        No inventory locations listed yet.
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4 animate-slide-up">{{ $locations->links() }}</div>
@endsection
