@extends('layouts.app')
@section('title', Auth::user()->isDonor() ? 'Donation Claims' : (Auth::user()->isNgo() ? 'My Claims' : 'Platform Claims'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 animate-slide-up">
    <div>
        <h2 style="font-weight: 600;" class="mb-1">
            <i class="bi bi-hand-thumbs-up text-apple-accent"></i> 
            @if(Auth::user()->isDonor())
                Donation Claims Received
            @elseif(Auth::user()->isNgo())
                My Claims
            @else
                Platform Claims
            @endif
        </h2>
        <p class="mb-0" style="color: var(--apple-text-muted);">
            @if(Auth::user()->isDonor())
                Track and manage claim requests submitted by NGOs for your published food listings.
            @elseif(Auth::user()->isNgo())
                Track and manage your claimed food donation requests.
            @else
                Overview and logistics management of all platform claims.
            @endif
        </p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <div class="btn-group btn-group-sm shadow-sm" role="group" style="border-radius: 20px; overflow: hidden; border: 1px solid var(--apple-border);">
            <button type="button" class="btn btn-sm btn-outline-light active px-3" id="btnTableView" onclick="setClaimsView('table')">
                <i class="bi bi-table me-1"></i> Table
            </button>
            <button type="button" class="btn btn-sm btn-outline-light px-3" id="btnGridView" onclick="setClaimsView('grid')">
                <i class="bi bi-grid-3x3-gap me-1"></i> Visual Cards
            </button>
        </div>
    </div>
</div>

<!-- Table View Container -->
<div class="card shadow-sm animate-slide-up mb-4" id="claimsTableView">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-list-check text-apple-accent"></i> Claimed Donations List</span>
        <span class="badge border px-3 py-1" style="background-color: var(--apple-input-bg); color: var(--apple-text); border-color: var(--apple-border) !important; font-size: 0.75rem; font-weight: 500;">
            {{ $claims->total() }} Total Claims
        </span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr class="border-bottom" style="border-color: var(--apple-border) !important;">
                        <th class="ps-4 py-3 fw-semibold small" style="color: var(--apple-text-muted);">Donation Title</th>
                        @if(Auth::user()->isDonor())
                            <th class="py-3 fw-semibold small" style="color: var(--apple-text-muted);">Claiming NGO</th>
                        @elseif(Auth::user()->isNgo())
                            <th class="py-3 fw-semibold small" style="color: var(--apple-text-muted);">Donor</th>
                        @else
                            <th class="py-3 fw-semibold small" style="color: var(--apple-text-muted);">NGO / Donor</th>
                        @endif
                        <th class="py-3 fw-semibold small" style="color: var(--apple-text-muted);">Status</th>
                        <th class="py-3 fw-semibold small" style="color: var(--apple-text-muted);">Pickup Date</th>
                        <th class="py-3 fw-semibold small" style="color: var(--apple-text-muted);">Transport</th>
                        <th class="py-3 fw-semibold small" style="color: var(--apple-text-muted);">Receipt</th>
                        <th class="pe-4 py-3 fw-semibold small text-end" style="color: var(--apple-text-muted);">Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($claims as $claim)
                <tr class="border-bottom" style="border-color: var(--apple-border) !important;">
                    <td class="ps-4">
                        <strong style="color: var(--apple-text);">{{ $claim->donation->title }}</strong>
                        <br><small style="color: var(--apple-text-muted); font-size: 0.78rem;">{{ $claim->donation->quantity }} {{ $claim->donation->unit }}</small>
                    </td>
                    <td>
                        @if(Auth::user()->isDonor())
                            <strong style="color: var(--apple-text);">{{ $claim->user->organization_name ?? $claim->user->name }}</strong>
                        @elseif(Auth::user()->isNgo())
                            <strong style="color: var(--apple-text);">{{ $claim->donation->donor->organization_name ?? $claim->donation->donor->name }}</strong>
                        @else
                            <strong style="color: var(--apple-text);">{{ $claim->user->organization_name ?? $claim->user->name }}</strong>
                            <br><small style="color: var(--apple-text-muted); font-size: 0.75rem;">Donor: {{ $claim->donation->donor->name }}</small>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-{{ $claim->status === 'approved' ? 'success' : ($claim->status === 'pending' ? 'warning' : ($claim->status === 'collected' ? 'info' : 'secondary')) }}">
                            {{ ucfirst($claim->status) }}
                        </span>
                    </td>
                    <td class="small" style="color: var(--apple-text-muted);"><i class="bi bi-calendar me-1"></i>{{ $claim->pickup_scheduled_at?->format('d M Y') ?? 'TBD' }}</td>
                    <td>
                        @if($claim->vehicle)
                            <span class="action-tag"><i class="bi bi-truck me-1"></i>{{ $claim->vehicle->plate_number }}</span>
                        @else
                            <span style="color: var(--apple-text-muted);">—</span>
                        @endif
                    </td>
                    <td>
                        @if($claim->collectionReceipt)
                            <span class="action-tag"><i class="bi bi-receipt me-1"></i>{{ $claim->collectionReceipt->receipt_number }}</span>
                        @else
                            <span style="color: var(--apple-text-muted);">—</span>
                        @endif
                    </td>
                    <td class="pe-4 text-end">
                        <a href="{{ route('claims.show', $claim) }}" class="btn btn-sm btn-outline-light text-nowrap">
                            <i class="bi bi-eye"></i> View Details
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="py-4">
                            <div class="mb-3 d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 56px; height: 56px; background: rgba(41, 151, 255, 0.1); color: var(--apple-accent);">
                                <i class="bi bi-hand-thumbs-up" style="font-size: 1.6rem;"></i>
                            </div>
                            <h6 class="fw-bold mb-1" style="color: var(--apple-text);">No Claims Found</h6>
                            <p class="mb-0 small" style="color: var(--apple-text-muted);">
                                @if(Auth::user()->isDonor())
                                    No claims have been submitted by NGOs for your published food listings yet.
                                @elseif(Auth::user()->isNgo())
                                    You have not claimed any food donations yet.
                                @else
                                    No claims recorded across the platform yet.
                                @endif
                            </p>
                        </div>
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Visual Cards Grid View Container (Hidden by Default) -->
<div class="row g-3 animate-slide-up mb-4" id="claimsGridView" style="display: none;">
    @forelse($claims as $claim)
    <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm h-100 border" style="border-color: var(--apple-border) !important; border-radius: 16px; overflow: hidden;">
            <div class="card-body p-4 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="fw-bold mb-0" style="color: var(--apple-text);">Claim #{{ $claim->id }}: {{ $claim->donation->title }}</h6>
                    <span class="badge badge-{{ $claim->status === 'approved' ? 'success' : ($claim->status === 'pending' ? 'warning' : ($claim->status === 'collected' ? 'info' : 'secondary')) }}">
                        {{ ucfirst($claim->status) }}
                    </span>
                </div>
                
                <div class="small mb-3" style="color: var(--apple-text-muted);">
                    <i class="bi bi-box me-1 text-apple-accent"></i> <strong>{{ $claim->donation->quantity }} {{ $claim->donation->unit }}</strong>
                </div>

                <div class="mt-auto pt-3 border-top" style="border-color: var(--apple-border) !important; font-size: 0.83rem;">
                    <div class="d-flex justify-content-between mb-1" style="color: var(--apple-text-muted);">
                        <span><i class="bi bi-building text-apple-accent me-1"></i> NGO:</span>
                        <strong style="color: var(--apple-text);">{{ Str::limit($claim->user->organization_name ?? $claim->user->name, 20) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1" style="color: var(--apple-text-muted);">
                        <span><i class="bi bi-person text-apple-accent me-1"></i> Donor:</span>
                        <span style="color: var(--apple-text);">{{ Str::limit($claim->donation->donor->organization_name ?? $claim->donation->donor->name, 20) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1" style="color: var(--apple-text-muted);">
                        <span><i class="bi bi-truck text-apple-accent me-1"></i> Transport:</span>
                        @if($claim->vehicle)
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">{{ $claim->vehicle->plate_number }}</span>
                        @else
                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">Pending</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between" style="color: var(--apple-text-muted);">
                        <span><i class="bi bi-calendar text-apple-accent me-1"></i> Pickup:</span>
                        <span>{{ $claim->pickup_scheduled_at?->format('d M Y') ?? 'TBD' }}</span>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-top-0 p-3 pt-0">
                <a href="{{ route('claims.show', $claim) }}" class="btn btn-sm btn-outline-light w-100">
                    <i class="bi bi-eye me-1"></i> View Details
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <div class="card shadow-sm border p-5 mx-auto text-center" style="max-width: 480px; background: var(--apple-surface); border-color: var(--apple-border) !important; border-radius: 20px;">
            <div class="mb-3 d-inline-flex align-items-center justify-content-center rounded-circle mx-auto" style="width: 64px; height: 64px; background: rgba(41, 151, 255, 0.1); color: var(--apple-accent);">
                <i class="bi bi-hand-thumbs-up" style="font-size: 2rem;"></i>
            </div>
            <h5 class="fw-bold mb-2" style="color: var(--apple-text);">No Claims Found</h5>
            <p class="mb-3 small" style="color: var(--apple-text-muted);">
                @if(Auth::user()->isDonor())
                    No claims have been submitted by NGOs for your published food listings yet.
                @elseif(Auth::user()->isNgo())
                    You have not claimed any food donations yet.
                @else
                    No claims recorded across the platform yet.
                @endif
            </p>
            @if(Auth::user()->isNgo())
                <div>
                    <a href="{{ route('donations.index') }}" class="btn btn-ns-primary btn-sm">
                        <i class="bi bi-search me-1"></i> Browse Donations
                    </a>
                </div>
            @endif
        </div>
    </div>
    @endforelse
</div>

<div class="mt-4 animate-slide-up">{{ $claims->links() }}</div>

@push('scripts')
<script>
function setClaimsView(mode) {
    const tableView = document.getElementById('claimsTableView');
    const gridView = document.getElementById('claimsGridView');
    const btnTable = document.getElementById('btnTableView');
    const btnGrid = document.getElementById('btnGridView');

    if (mode === 'grid') {
        tableView.style.display = 'none';
        gridView.style.display = 'flex';
        btnTable.classList.remove('active');
        btnGrid.classList.add('active');
    } else {
        tableView.style.display = 'block';
        gridView.style.display = 'none';
        btnGrid.classList.remove('active');
        btnTable.classList.add('active');
    }
    localStorage.setItem('claimsViewPreference', mode);
}

document.addEventListener('DOMContentLoaded', function() {
    const savedMode = localStorage.getItem('claimsViewPreference') || 'table';
    setClaimsView(savedMode);
});
</script>
@endpush
@endsection
