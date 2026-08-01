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
</div>

<div class="card shadow-sm animate-slide-up">
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
                        <th class="py-3 fw-semibold small" style="color: var(--apple-text-muted);">Vehicle</th>
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
                    <td colspan="7" class="text-center py-5" style="color: var(--apple-text-muted);">
                        @if(Auth::user()->isDonor())
                            No claims received on your food donations yet.
                        @elseif(Auth::user()->isNgo())
                            No claims placed yet.
                        @else
                            No claims recorded on the platform yet.
                        @endif
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4 animate-slide-up">{{ $claims->links() }}</div>
@endsection
