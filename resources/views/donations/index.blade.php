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
    @if(Auth::user()->isDonor() || Auth::user()->isAdmin() || Auth::user()->isModerator())
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

<!-- View Switcher Controls -->
<div class="d-flex justify-content-between align-items-center mb-3 animate-slide-up">
    <div class="small" style="color: var(--apple-text-muted);">
        Showing <strong style="color: var(--apple-text);">{{ $donations->count() }}</strong> of <strong style="color: var(--apple-text);">{{ $donations->total() }}</strong> listings
    </div>
    <div class="btn-group btn-group-sm" role="group">
        <button type="button" class="btn btn-outline-light active" id="btnTableView" onclick="setDonationsView('table')">
            <i class="bi bi-list-ul me-1"></i> Table View
        </button>
        <button type="button" class="btn btn-outline-light" id="btnGridView" onclick="setDonationsView('grid')">
            <i class="bi bi-grid-3x3-gap me-1"></i> Visual Cards View
        </button>
    </div>
</div>

<div id="donationsContentArea">
    <!-- Table View Container -->
    <div class="card shadow-sm animate-slide-up" id="donationsTableView">
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
                        <td colspan="7" class="text-center py-5">
                            <div class="py-4">
                                <div class="mb-3 d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 56px; height: 56px; background: rgba(41, 151, 255, 0.1); color: var(--apple-accent);">
                                    <i class="bi bi-basket" style="font-size: 1.6rem;"></i>
                                </div>
                                <h6 class="fw-bold mb-1" style="color: var(--apple-text);">No Donations Found</h6>
                                <p class="mb-0 small" style="color: var(--apple-text-muted);">No surplus food donations match your search criteria.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Visual Cards View Container (Hidden by Default or Toggleable) -->
    <div class="row g-3 animate-slide-up" id="donationsGridView" style="display: none;">
        @forelse($donations as $donation)
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm h-100 border" style="border-color: var(--apple-border) !important; border-radius: 16px; overflow: hidden;">
                @if($donation->image_paths && count($donation->image_paths) > 0)
                    @php
                        $thumbSrc = Str::startsWith($donation->image_paths[0], ['http://', 'https://']) ? $donation->image_paths[0] : asset('storage/' . $donation->image_paths[0]);
                    @endphp
                    <div style="height: 160px; overflow: hidden; background: rgba(0,0,0,0.2);">
                        <img src="{{ $thumbSrc }}" class="w-100 h-100" style="object-fit: cover; transition: transform 0.3s ease;" alt="{{ $donation->title }}">
                    </div>
                @else
                    <div class="d-flex justify-content-center align-items-center" style="height: 140px; background: rgba(255,255,255,0.03);">
                        <i class="bi bi-basket text-muted" style="font-size: 2.5rem; opacity: 0.5;"></i>
                    </div>
                @endif
                <div class="card-body d-flex flex-column p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="fw-bold mb-0" style="color: var(--apple-text);">{{ $donation->title }}</h6>
                        <span class="badge badge-{{ $donation->status === 'available' ? 'success' : ($donation->status === 'claimed' ? 'warning' : 'secondary') }}">
                            {{ ucfirst($donation->status) }}
                        </span>
                    </div>
                    <p class="small mb-3" style="color: var(--apple-text-muted); line-height: 1.4;">{{ Str::limit($donation->description, 75) }}</p>
                    
                    <div class="mt-auto small pt-2 border-top" style="border-color: var(--apple-border) !important; color: var(--apple-text-muted);">
                        <div class="d-flex justify-content-between mb-1">
                            <span><i class="bi bi-box text-apple-accent me-1"></i> Quantity:</span>
                            <strong style="color: var(--apple-text);">{{ $donation->quantity }} {{ $donation->unit }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span><i class="bi bi-building text-apple-accent me-1"></i> Donor:</span>
                            <span style="color: var(--apple-text);">{{ Str::limit($donation->donor->organization_name ?? $donation->donor->name, 20) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span><i class="bi bi-calendar text-apple-accent me-1"></i> Expires:</span>
                            <span>{{ $donation->expiry_date->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-0 p-3 pt-0">
                    <a href="{{ route('donations.show', $donation) }}" class="btn btn-sm btn-outline-light w-100">
                        <i class="bi bi-eye me-1"></i> View Details
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <div class="card shadow-sm border p-5 mx-auto text-center" style="max-width: 480px; background: var(--apple-surface); border-color: var(--apple-border) !important; border-radius: 20px;">
                <div class="mb-3 d-inline-flex align-items-center justify-content-center rounded-circle mx-auto" style="width: 64px; height: 64px; background: rgba(41, 151, 255, 0.1); color: var(--apple-accent);">
                    <i class="bi bi-basket" style="font-size: 2rem;"></i>
                </div>
                <h5 class="fw-bold mb-2" style="color: var(--apple-text);">No Donations Found</h5>
                <p class="mb-3 small" style="color: var(--apple-text-muted);">No surplus food donations match your search criteria.</p>
                @if(Auth::user()->isDonor() || Auth::user()->isAdmin() || Auth::user()->isModerator())
                    <div>
                        <a href="{{ route('donations.create') }}" class="btn btn-ns-primary btn-sm">
                            <i class="bi bi-plus-circle me-1"></i> Publish Donation
                        </a>
                    </div>
                @endif
            </div>
        </div>
        @endforelse
    </div>
</div>

<div class="mt-4 animate-slide-up" id="paginationContainer">{{ $donations->links() }}</div>

@push('scripts')
<script>
function setDonationsView(mode) {
    const tableView = document.getElementById('donationsTableView');
    const gridView = document.getElementById('donationsGridView');
    const btnTable = document.getElementById('btnTableView');
    const btnGrid = document.getElementById('btnGridView');

    if (mode === 'grid') {
        tableView.style.display = 'none';
        gridView.style.display = 'flex';
        btnTable.classList.remove('active');
        btnGrid.classList.add('active');
        localStorage.setItem('donations_view_mode', 'grid');
    } else {
        gridView.style.display = 'none';
        tableView.style.display = 'block';
        btnGrid.classList.remove('active');
        btnTable.classList.add('active');
        localStorage.setItem('donations_view_mode', 'table');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const savedMode = localStorage.getItem('donations_view_mode') || 'table';
    setDonationsView(savedMode);

    const searchInput = document.getElementById('searchInput');
    const clearBtn = document.getElementById('clearSearch');
    const contentArea = document.getElementById('donationsContentArea');
    const pagination = document.getElementById('paginationContainer');
    let debounceTimer;

    const toggleClearBtn = () => {
        clearBtn.style.display = searchInput.value.length > 0 ? 'block' : 'none';
    };
    toggleClearBtn();

    clearBtn.addEventListener('click', () => {
        searchInput.value = '';
        toggleClearBtn();
        triggerSearch();
    });

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
                
                const newContent = doc.getElementById('donationsContentArea');
                if (newContent && contentArea) {
                    contentArea.innerHTML = newContent.innerHTML;
                    const currentMode = localStorage.getItem('donations_view_mode') || 'table';
                    setDonationsView(currentMode);
                }
                
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
