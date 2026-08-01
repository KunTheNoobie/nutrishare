@extends('layouts.app')
@section('title', 'Donations')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 animate-slide-up">
    <div>
        <h2 style="font-weight: 600;" class="mb-1">
            <i class="bi bi-basket text-apple-accent"></i> 
            @if(Auth::user()->isDonor()) My Donations @elseif(Auth::user()->isAdmin() || Auth::user()->isModerator()) Platform Donations @else Available Donations @endif
        </h2>
        <p class="mb-0" style="color: var(--apple-text-muted);">Browse, track, and manage food donation listings on NutriShare.</p>
    </div>
    @if(Auth::user()->isDonor() || Auth::user()->isAdmin())
    <a href="{{ route('donations.create') }}" class="btn btn-ns-primary">
        <i class="bi bi-plus-circle"></i> Publish Donation
    </a>
    @endif
</div>

<!-- Search & Filter Bar -->
<div class="card shadow-sm mb-4 animate-slide-up">
    <div class="card-body p-2">
        <form method="GET" action="{{ route('donations.index') }}" class="row g-2 align-items-center m-0" id="searchForm">
            <div class="col-md-{{ (Auth::user()->isAdmin() || Auth::user()->isModerator()) ? '9' : '12' }} position-relative">
                <i class="bi bi-search text-apple-accent position-absolute" style="left: 15px; top: 50%; transform: translateY(-50%);"></i>
                <input type="text" name="search" id="searchInput" class="form-control border-0 bg-transparent" placeholder="Search donations or donors..."
                       value="{{ request('search') }}" style="box-shadow: none; padding-left: 40px; padding-right: 40px;">
                <i class="bi bi-x-circle-fill text-muted position-absolute" id="clearSearch" style="right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; display: none;"></i>
            </div>
            @if(Auth::user()->isAdmin() || Auth::user()->isModerator())
            <div class="col-md-3">
                <select name="status" id="statusFilter" class="form-select border-0 bg-transparent text-light" style="box-shadow: none; border-left: 1px solid rgba(255,255,255,0.1) !important; border-radius: 0;">
                    <option value="all">All Statuses</option>
                    <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Available</option>
                    <option value="claimed" {{ request('status') === 'claimed' ? 'selected' : '' }}>Claimed</option>
                    <option value="collected" {{ request('status') === 'collected' ? 'selected' : '' }}>Collected</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>
            @endif
        </form>
    </div>
</div>

<!-- Donations Unified Card Table -->
<div class="card shadow-sm animate-slide-up" id="donationsTableContainer">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-list-check text-apple-accent me-1"></i> Food Donations List</span>
        <span class="badge border px-3 py-1" style="background-color: var(--apple-input-bg); color: var(--apple-text); border-color: var(--apple-border) !important; font-size: 0.75rem; font-weight: 500;">
            {{ $donations->total() }} Total Donations
        </span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr class="border-bottom" style="border-color: var(--apple-border) !important;">
                        <th class="ps-4 py-3 fw-semibold small" style="color: var(--apple-text-muted);">Donation Title</th>
                        <th class="py-3 fw-semibold small" style="color: var(--apple-text-muted);">Donor</th>
                        <th class="py-3 fw-semibold small" style="color: var(--apple-text-muted);">Quantity</th>
                        <th class="py-3 fw-semibold small" style="color: var(--apple-text-muted);">Pickup Location</th>
                        <th class="py-3 fw-semibold small" style="color: var(--apple-text-muted);">Expires</th>
                        <th class="py-3 fw-semibold small" style="color: var(--apple-text-muted);">Status</th>
                        <th class="pe-4 py-3 fw-semibold small text-end" style="color: var(--apple-text-muted);">Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($donations as $donation)
                <tr class="border-bottom" style="border-color: var(--apple-border) !important;">
                    <td class="ps-4">
                        <div class="d-flex align-items-center gap-3">
                            @if($donation->image_paths && count($donation->image_paths) > 0)
                                @php
                                    $thumbSrc = Str::startsWith($donation->image_paths[0], ['http://', 'https://']) ? $donation->image_paths[0] : asset('storage/' . $donation->image_paths[0]);
                                @endphp
                                <img src="{{ $thumbSrc }}" class="rounded shadow-sm" style="width: 40px; height: 40px; object-fit: cover;" alt="Thumbnail">
                            @else
                                <div class="rounded shadow-sm d-flex justify-content-center align-items-center" style="width: 40px; height: 40px; background: rgba(255,255,255,0.05);">
                                    <i class="bi bi-basket text-muted"></i>
                                </div>
                            @endif
                            <div>
                                <strong style="color: var(--apple-text);">
                                    <a href="{{ route('donations.show', $donation) }}" class="text-decoration-none" style="color: var(--apple-text);">{{ $donation->title }}</a>
                                </strong>
                                @if($donation->description)
                                <br><small style="color: var(--apple-text-muted); font-size: 0.78rem;">{{ Str::limit($donation->description, 50) }}</small>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <strong style="color: var(--apple-text);">{{ $donation->donor->organization_name ?? $donation->donor->name }}</strong>
                        <br><small style="color: var(--apple-text-muted); font-size: 0.75rem;">{{ $donation->donor->email }}</small>
                    </td>
                    <td style="color: var(--apple-text);">
                        <strong>{{ $donation->quantity }}</strong> {{ $donation->unit }}
                    </td>
                    <td class="small" style="color: var(--apple-text-muted);">
                        <i class="bi bi-geo-alt text-apple-accent me-1"></i>{{ Str::limit($donation->pickup_address, 35) }}
                    </td>
                    <td class="small" style="color: var(--apple-text-muted);">
                        <i class="bi bi-calendar me-1"></i>{{ $donation->expiry_date->format('d M Y') }}
                    </td>
                    <td>
                        <span class="badge badge-{{ $donation->status === 'available' ? 'success' : ($donation->status === 'claimed' ? 'warning' : ($donation->status === 'collected' ? 'info' : 'secondary')) }}">
                            {{ ucfirst($donation->status) }}
                        </span>
                    </td>
                    <td class="pe-4 text-end">
                        <a href="{{ route('donations.show', $donation) }}" class="btn btn-sm btn-outline-light text-nowrap">
                            <i class="bi bi-eye"></i> View Details
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5" style="color: var(--apple-text-muted);">
                        No donations found matching your search.
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4 animate-slide-up" id="paginationContainer">{{ $donations->links() }}</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const clearBtn = document.getElementById('clearSearch');
    const tableContainer = document.getElementById('donationsTableContainer');
    const pagination = document.getElementById('paginationContainer');
    let debounceTimer;

    // Toggle clear button visibility
    const toggleClearBtn = () => {
        clearBtn.style.display = searchInput.value.length > 0 ? 'block' : 'none';
    };
    toggleClearBtn();

    // Clear search
    clearBtn.addEventListener('click', () => {
        searchInput.value = '';
        toggleClearBtn();
        triggerSearch();
    });

    // Live search on typing
    searchInput.addEventListener('input', function() {
        toggleClearBtn();
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            triggerSearch();
        }, 300);
    });

    function triggerSearch() {
        const query = searchInput.value;
        const statusDropdown = document.getElementById('statusFilter');
        const status = statusDropdown ? statusDropdown.value : 'all';
        
        const url = new URL(window.location.href);
        if(query) {
            url.searchParams.set('search', query);
        } else {
            url.searchParams.delete('search');
        }
        
        if (status && status !== 'all') {
            url.searchParams.set('status', status);
        } else {
            url.searchParams.delete('status');
        }
        
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                const newTable = doc.getElementById('donationsTableContainer');
                if (newTable && tableContainer) tableContainer.innerHTML = newTable.innerHTML;
                
                const newPagination = doc.getElementById('paginationContainer');
                if (newPagination && pagination) pagination.innerHTML = newPagination.innerHTML;
                
                window.history.pushState({}, '', url);
            });
    }

    const statusDropdown = document.getElementById('statusFilter');
    if (statusDropdown) {
        statusDropdown.addEventListener('change', triggerSearch);
    }
});
</script>
@endpush
@endsection
