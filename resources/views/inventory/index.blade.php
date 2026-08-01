@extends('layouts.app')
@section('title', 'Inventory Locations')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 animate-slide-up">
    <div>
        <h2 style="font-weight: 600;" class="mb-1"><i class="bi bi-box-seam text-apple-accent"></i> Inventory Locations</h2>
        <p class="mb-0" style="color: var(--apple-text-muted);">Manage food storage facilities, capacity, and inventory safety audits.</p>
    </div>
    @if(Auth::user()->isNgo() || Auth::user()->isAdmin() || Auth::user()->isModerator())
    <a href="{{ route('inventory.create') }}" class="btn btn-ns-primary">
        <i class="bi bi-plus-circle"></i> Add Location
    </a>
    @endif
</div>

<!-- View Switcher Controls -->
<div class="d-flex justify-content-between align-items-center mb-3 animate-slide-up">
    <div class="small" style="color: var(--apple-text-muted);">
        Showing <strong style="color: var(--apple-text);">{{ $locations->count() }}</strong> of <strong style="color: var(--apple-text);">{{ $locations->total() }}</strong> facilities
    </div>
    <div class="btn-group btn-group-sm shadow-sm" role="group" style="border-radius: 20px; overflow: hidden; border: 1px solid var(--apple-border);">
        <button type="button" class="btn btn-sm btn-outline-light active px-3" id="btnTableView" onclick="setInventoryView('table')">
            <i class="bi bi-table me-1"></i> Table View
        </button>
        <button type="button" class="btn btn-sm btn-outline-light px-3" id="btnGridView" onclick="setInventoryView('grid')">
            <i class="bi bi-grid-3x3-gap me-1"></i> Visual Cards View
        </button>
    </div>
</div>

<div id="inventoryContentArea">
    <!-- Table View Container -->
    <div class="card shadow-sm animate-slide-up" id="inventoryTableView">
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
                        <td colspan="5" class="text-center py-5">
                            <div class="py-4">
                                <div class="mb-3 d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 56px; height: 56px; background: rgba(52, 199, 89, 0.1); color: var(--apple-success);">
                                    <i class="bi bi-box-seam" style="font-size: 1.6rem;"></i>
                                </div>
                                <h6 class="fw-bold mb-1" style="color: var(--apple-text);">No Inventory Locations Listed Yet</h6>
                                <p class="mb-0 small" style="color: var(--apple-text-muted);">No food storage facilities or inventory pantries have been added to NutriShare.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Facility Cards View Container (Hidden by Default or Toggleable) -->
    <div class="row g-3 animate-slide-up" id="inventoryGridView" style="display: none;">
        @forelse($locations as $location)
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm h-100 border" style="border-color: var(--apple-border) !important; border-radius: 16px;">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 style="font-weight: 600;" class="mb-0" style="color: var(--apple-text);">{{ $location->name }}</h5>
                        @if($location->user)
                        <span class="badge border px-2.5 py-1 text-nowrap ms-2" style="background-color: var(--apple-input-bg); color: var(--apple-text); border-color: var(--apple-border) !important; font-size: 0.75rem; font-weight: 500;">
                            <i class="bi bi-building me-1 text-apple-accent"></i>{{ Str::limit($location->user->organization_name ?? $location->user->name, 18) }}
                        </span>
                        @endif
                    </div>
                    <p class="text-muted mb-3 small">{{ Str::limit($location->address, 50) }}</p>
                    <div>
                        <span class="badge badge-{{ $location->storage_type === 'cold' ? 'donor' : ($location->storage_type === 'frozen' ? 'admin' : 'success') }}">
                            {{ ucfirst($location->storage_type) }} Storage
                        </span>
                    </div>
                    @if($location->capacity)
                    <div class="mt-3">
                        <div class="d-flex justify-content-between small mb-1" style="color: var(--apple-text-muted);">
                            <span>Capacity:</span>
                            <strong style="color: var(--apple-text);">{{ number_format($location->current_occupancy, 1) }}/{{ number_format($location->capacity, 1) }} kg</strong>
                        </div>
                        <div class="progress" style="height: 6px; background-color: var(--apple-border);">
                            <div class="progress-bar" style="width:{{ min(($location->current_occupancy / max($location->capacity, 1)) * 100, 100) }}%; background-color: var(--apple-accent) !important;"></div>
                        </div>
                    </div>
                    @endif
                    <div class="mt-auto pt-3">
                        <small style="color: var(--apple-text-muted);"><i class="bi bi-list-ul text-apple-accent me-1"></i> <strong>{{ $location->food_items_count }}</strong> items stored</small>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-0 p-3 pt-0">
                    <a href="{{ route('inventory.show', $location) }}" class="btn btn-sm btn-outline-light w-100">
                        @if(Auth::user()->isNgo() && $location->user_id === Auth::id())
                            <i class="bi bi-gear me-1"></i> Manage Pantry
                        @else
                            <i class="bi bi-shield-check me-1"></i> Audit Inventory
                        @endif
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <div class="card shadow-sm border p-5 mx-auto text-center" style="max-width: 480px; background: var(--apple-surface); border-color: var(--apple-border) !important; border-radius: 20px;">
                <div class="mb-3 d-inline-flex align-items-center justify-content-center rounded-circle mx-auto" style="width: 64px; height: 64px; background: rgba(52, 199, 89, 0.1); color: var(--apple-success);">
                    <i class="bi bi-box-seam" style="font-size: 2rem;"></i>
                </div>
                <h5 class="fw-bold mb-2" style="color: var(--apple-text);">No Inventory Locations Listed Yet</h5>
                <p class="mb-3 small" style="color: var(--apple-text-muted);">No food storage facilities or inventory pantries have been added to NutriShare.</p>
                @if(Auth::user()->isNgo() || Auth::user()->isAdmin() || Auth::user()->isModerator())
                    <div>
                        <a href="{{ route('inventory.create') }}" class="btn btn-ns-primary btn-sm">
                            <i class="bi bi-plus-circle me-1"></i> Add Location
                        </a>
                    </div>
                @endif
            </div>
        </div>
        @endforelse
    </div>
</div>

<div class="mt-4 animate-slide-up">{{ $locations->links() }}</div>

@push('scripts')
<script>
function setInventoryView(mode) {
    const tableView = document.getElementById('inventoryTableView');
    const gridView = document.getElementById('inventoryGridView');
    const btnTable = document.getElementById('btnTableView');
    const btnGrid = document.getElementById('btnGridView');

    if (mode === 'grid') {
        tableView.style.display = 'none';
        gridView.style.display = 'flex';
        btnTable.classList.remove('active');
        btnGrid.classList.add('active');
        localStorage.setItem('inventory_view_mode', 'grid');
    } else {
        gridView.style.display = 'none';
        tableView.style.display = 'block';
        btnGrid.classList.remove('active');
        btnTable.classList.add('active');
        localStorage.setItem('inventory_view_mode', 'table');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const savedMode = localStorage.getItem('inventory_view_mode') || 'table';
    setInventoryView(savedMode);
});
</script>
@endpush
@endsection
