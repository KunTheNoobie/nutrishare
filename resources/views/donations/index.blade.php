@extends('layouts.app')
@section('title', 'Donations')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 animate-slide-up">
    <h2>
        <i class="bi bi-basket text-apple-accent"></i> 
        @if(Auth::user()->isDonor()) My Donations @else Available Donations @endif
    </h2>
    @if(Auth::user()->isDonor() || Auth::user()->isAdmin())
    <a href="{{ route('donations.create') }}" class="btn btn-ns-primary">
        <i class="bi bi-plus-circle"></i> Publish Donation
    </a>
    @endif
</div>

<!-- Search -->
<div class="card shadow-sm mb-4 animate-slide-up">
    <div class="card-body p-2">
        <form method="GET" action="{{ route('donations.index') }}" class="row g-2 align-items-center m-0" id="searchForm">
            <div class="col-12 position-relative">
                <i class="bi bi-search text-apple-accent position-absolute" style="left: 15px; top: 50%; transform: translateY(-50%);"></i>
                <input type="text" name="search" id="searchInput" class="form-control border-0 bg-transparent" placeholder="Search donations..."
                       value="{{ request('search') }}" style="box-shadow: none; padding-left: 40px; padding-right: 40px;">
                <i class="bi bi-x-circle-fill text-muted position-absolute" id="clearSearch" style="right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; display: none;"></i>
            </div>
        </form>
    </div>
</div>

<!-- Donations Grid -->
<div class="row g-3 animate-slide-up" id="donationsGrid" style="animation-delay: 0.1s;">
    @forelse($donations as $donation)
    <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm h-100">
            @if($donation->image_paths && count($donation->image_paths) > 0)
                @php
                    $thumbSrc = Str::startsWith($donation->image_paths[0], ['http://', 'https://']) ? $donation->image_paths[0] : asset('storage/' . $donation->image_paths[0]);
                @endphp
                <img src="{{ $thumbSrc }}" class="card-img-top" alt="Donation Image" style="height: 150px; object-fit: cover;">
            @endif
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
            <p class="mt-3 text-muted" style="font-size: 1.1rem;">No donations found.</p>
        </div>
    </div>
    @endforelse
</div>

<div class="mt-4" id="paginationContainer">{{ $donations->links() }}</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const clearBtn = document.getElementById('clearSearch');
    const grid = document.getElementById('donationsGrid');
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
        const url = new URL(window.location.href);
        if(query) {
            url.searchParams.set('search', query);
        } else {
            url.searchParams.delete('search');
        }
        
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                const newGrid = doc.getElementById('donationsGrid');
                if (newGrid) grid.innerHTML = newGrid.innerHTML;
                
                const newPagination = doc.getElementById('paginationContainer');
                if (newPagination && pagination) pagination.innerHTML = newPagination.innerHTML;
                
                window.history.pushState({}, '', url);
            });
    }
});
</script>
@endpush
@endsection
