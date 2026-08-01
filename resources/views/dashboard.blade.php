@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 animate-slide-up">
    <div>
        {{-- SECURITY (Module 1): XSS Prevention — {{ }} escapes user-provided name --}}
        <h2>Welcome, {{ $user->name }}!</h2>
        <p class="text-muted mb-0">
            <span class="badge badge-{{ $user->role === 'admin' ? 'admin' : ($user->role === 'moderator' ? 'moderator' : ($user->role === 'ngo' ? 'ngo' : 'donor')) }}">
                {{ $user->role === 'ngo' ? 'NGO' : ucfirst($user->role) }}
            </span>
            @if($user->isNgo())
                <span class="badge badge-{{ $user->isVerified() ? 'success' : 'warning' }}">
                    {{ $user->isVerified() ? 'Verified' : 'Pending Verification' }}
                </span>
            @endif
        </p>
    </div>
</div>

{{-- ── Donor Dashboard ── --}}
@if($user->isDonor())
<div class="row g-3 mb-4 animate-slide-up">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center py-4">
                <h2 class="text-apple-accent mb-2" style="font-size: 2.5rem; font-weight: 600;">{{ $totalDonations }}</h2>
                <p class="text-muted mb-0" style="font-size: 0.95rem;">Total Donations</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center py-4">
                <h2 class="text-apple-success mb-2" style="font-size: 2.5rem; font-weight: 600;">{{ $activeDonations }}</h2>
                <p class="text-muted mb-0" style="font-size: 0.95rem;">Active Donations</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm h-100 d-flex justify-content-center align-items-center">
            <div class="card-body text-center py-4 w-100 d-flex flex-column justify-content-center">
                <a href="{{ route('donations.create') }}" class="btn btn-ns-primary w-100">
                    <i class="bi bi-plus-circle"></i> New Donation
                </a>
            </div>
        </div>
    </div>
</div>
<div class="card shadow-sm animate-slide-up">
    <div class="card-header"><i class="bi bi-basket text-apple-accent"></i> Recent Donations</div>
    <div class="card-body">
        @forelse($donations as $donation)
        <div class="d-flex justify-content-between align-items-center py-3 border-bottom border-dark">
            <div class="d-flex align-items-center gap-3">
                @if($donation->image_paths && count($donation->image_paths) > 0)
                    @php
                        $thumbSrc = Str::startsWith($donation->image_paths[0], ['http://', 'https://']) ? $donation->image_paths[0] : asset('storage/' . $donation->image_paths[0]);
                    @endphp
                    <img src="{{ $thumbSrc }}" class="rounded shadow-sm" style="width: 60px; height: 60px; object-fit: cover;" alt="Thumbnail">
                @else
                    <div class="rounded shadow-sm d-flex justify-content-center align-items-center" style="width: 60px; height: 60px; background: rgba(255,255,255,0.05);">
                        <i class="bi bi-basket text-muted fs-4"></i>
                    </div>
                @endif
                <div>
                    <strong style="font-size: 1.05rem;">
                        <a href="{{ route('donations.show', $donation) }}" class="text-decoration-none text-light">{{ $donation->title }}</a>
                    </strong>
                    <br><small class="text-muted">{{ $donation->quantity }} {{ $donation->unit }} &nbsp;&middot;&nbsp; Expires: {{ $donation->expiry_date->format('d M Y') }}</small>
                </div>
            </div>
            <div class="d-flex flex-column align-items-end gap-2">
                <span class="badge badge-{{ $donation->status === 'available' ? 'success' : ($donation->status === 'claimed' ? 'warning' : 'secondary') }}">
                    {{ ucfirst($donation->status) }}
                </span>
                <a href="{{ route('donations.show', $donation) }}" class="btn btn-sm btn-outline-light text-nowrap">View Details</a>
            </div>
        </div>
        @empty
        <p class="text-muted text-center py-4">No donations yet. <a href="{{ route('donations.create') }}">Create your first donation!</a></p>
        @endforelse
    </div>
</div>
@endif

{{-- ── NGO Dashboard ── --}}
@if($user->isNgo())
<div class="row g-3 mb-4 animate-slide-up">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center py-4">
                <h2 class="text-apple-accent mb-2" style="font-size: 2.5rem; font-weight: 600;">{{ $totalClaims }}</h2>
                <p class="text-muted mb-0" style="font-size: 0.95rem;">Total Claims</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center py-4">
                <h2 class="text-apple-warning mb-2" style="font-size: 2.5rem; font-weight: 600;">{{ $pendingClaims }}</h2>
                <p class="text-muted mb-0" style="font-size: 0.95rem;">Pending Claims</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm h-100 d-flex justify-content-center align-items-center">
            <div class="card-body text-center py-4 w-100 d-flex flex-column justify-content-center">
                <a href="{{ route('donations.index') }}" class="btn btn-ns-primary w-100">
                    <i class="bi bi-search"></i> Browse Donations
                </a>
            </div>
        </div>
    </div>
</div>

@if(!$user->isVerified())
    @if($user->verificationDocuments()->where('status', 'pending')->exists())
        <div class="card border-warning mb-4 animate-slide-up" style="border: 1px solid rgba(255, 149, 0, 0.4); background-color: rgba(255, 149, 0, 0.05);">
            <div class="card-body p-4 text-center">
                <h5 class="text-apple-warning mb-3"><i class="bi bi-clock-history"></i> Verification Pending</h5>
                <p class="text-muted mb-0">Your NGO document has been submitted and is currently under review by an administrator. Please check back later.</p>
            </div>
        </div>
    @else
        <div class="card border-warning mb-4 animate-slide-up" style="border: 1px solid rgba(255, 149, 0, 0.4);">
            <div class="card-body p-4">
                <h5 class="text-apple-warning mb-3"><i class="bi bi-exclamation-triangle"></i> Verification Required</h5>
                <p class="text-muted">Upload your NGO license to get verified by an admin.</p>
                <form method="POST" action="{{ route('verification.upload') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3 align-items-start">
                        <div class="col-md-4">
                            <select name="document_type" class="form-select @error('document_type') is-invalid @enderror" required>
                                <option value="license">NGO License</option>
                                <option value="registration_cert">Registration Certificate</option>
                                <option value="tax_exempt">Tax Exemption Letter</option>
                            </select>
                            @error('document_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-5">
                            <input type="file" name="document" class="form-control @error('document') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png" required>
                            @error('document')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-ns-primary w-100">Upload Document</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endif

<div class="card shadow-sm animate-slide-up">
    <div class="card-header"><i class="bi bi-hand-thumbs-up text-apple-success"></i> Recent Claims</div>
    <div class="card-body">
        @forelse($claims as $claim)
        <div class="d-flex justify-content-between align-items-center py-3 border-bottom border-dark">
            <div>
                <strong style="font-size: 1.05rem;">
                    <a href="{{ route('claims.show', $claim) }}" class="text-decoration-none text-light">{{ $claim->donation->title }}</a>
                </strong>
                <br><small class="text-muted">Claimed on {{ $claim->created_at->format('d M Y') }}</small>
            </div>
            <div class="d-flex flex-column align-items-end gap-2">
                <span class="badge badge-{{ $claim->status === 'approved' ? 'success' : ($claim->status === 'pending' ? 'warning' : 'secondary') }}">
                    {{ ucfirst($claim->status) }}
                </span>
                <a href="{{ route('claims.show', $claim) }}" class="btn btn-sm btn-outline-light text-nowrap">View Details</a>
            </div>
        </div>
        @empty
        <p class="text-muted text-center py-4">No claims yet. <a href="{{ route('donations.index') }}">Browse available donations!</a></p>
        @endforelse
    </div>
</div>

<div class="card shadow-sm animate-slide-up mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-basket text-apple-accent me-1"></i> Recent Available Donations</span>
        <div class="d-flex align-items-center gap-2">
            <span class="badge border px-3 py-1" style="background-color: var(--apple-input-bg); color: var(--apple-text); border-color: var(--apple-border) !important; font-size: 0.75rem; font-weight: 500;">
                <i class="bi bi-clock-history me-1"></i>Active Listings
            </span>
            <a href="{{ route('donations.index') }}" class="btn btn-sm btn-outline-light px-3" style="font-size: 0.78rem;">
                <i class="bi bi-arrow-right"></i> View All Donations
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr class="border-bottom" style="border-color: var(--apple-border) !important;">
                        <th class="ps-4 py-3 fw-semibold small" style="color: var(--apple-text-muted);">Donation Title</th>
                        <th class="py-3 fw-semibold small" style="color: var(--apple-text-muted);">Donor</th>
                        <th class="py-3 fw-semibold small" style="color: var(--apple-text-muted);">Quantity</th>
                        <th class="py-3 fw-semibold small" style="color: var(--apple-text-muted);">Status</th>
                        <th class="pe-4 py-3 fw-semibold small text-end" style="color: var(--apple-text-muted);">Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($recentDonations as $donation)
                <tr class="border-bottom" style="border-color: var(--apple-border) !important;">
                    <td class="ps-4">
                        <div class="d-flex align-items-center gap-3">
                            @if($donation->image_paths && count($donation->image_paths) > 0)
                                @php
                                    $thumbSrc = Str::startsWith($donation->image_paths[0], ['http://', 'https://']) ? $donation->image_paths[0] : asset('storage/' . $donation->image_paths[0]);
                                @endphp
                                <img src="{{ $thumbSrc }}" class="rounded shadow-sm" style="width: 36px; height: 36px; object-fit: cover;" alt="Thumbnail">
                            @else
                                <div class="rounded shadow-sm d-flex justify-content-center align-items-center" style="width: 36px; height: 36px; background: rgba(255,255,255,0.05);">
                                    <i class="bi bi-basket text-muted small"></i>
                                </div>
                            @endif
                            <strong style="color: var(--apple-text);">
                                <a href="{{ route('donations.show', $donation) }}" class="text-decoration-none" style="color: var(--apple-text);">{{ $donation->title }}</a>
                            </strong>
                        </div>
                    </td>
                    <td>
                        <strong style="color: var(--apple-text);">{{ $donation->donor->organization_name ?? $donation->donor->name }}</strong>
                        <br><small style="color: var(--apple-text-muted); font-size: 0.75rem;">{{ $donation->donor->email }}</small>
                    </td>
                    <td style="color: var(--apple-text);">
                        {{ $donation->quantity }} {{ $donation->unit }}
                    </td>
                    <td>
                        <span class="badge badge-{{ $donation->status === 'available' ? 'success' : ($donation->status === 'claimed' ? 'warning' : 'secondary') }}">
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
                    <td colspan="5" class="text-center py-5" style="color: var(--apple-text-muted);">No available donations at the moment.</td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

{{-- ── Admin / Moderator Dashboard ── --}}
@if($user->isAdmin() || $user->isModerator())
<div class="row g-3 mb-4 animate-slide-up">
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body text-center py-4">
                <h2 class="text-apple-danger mb-2" style="font-size: 2.5rem; font-weight: 600;">{{ $pendingVerifications }}</h2>
                <p class="text-muted mb-0" style="font-size: 0.95rem;">Pending Verifications</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body text-center py-4">
                <h2 class="text-apple-accent mb-2" style="font-size: 2.5rem; font-weight: 600;">{{ $totalUsers }}</h2>
                <p class="text-muted mb-0" style="font-size: 0.95rem;">Total Users</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body text-center py-4">
                <h2 class="text-apple-success mb-2" style="font-size: 2.5rem; font-weight: 600;">{{ $totalDonations }}</h2>
                <p class="text-muted mb-0" style="font-size: 0.95rem;">Total Donations</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm h-100 d-flex justify-content-center align-items-center">
            <div class="card-body text-center py-4 w-100 d-flex flex-column justify-content-center gap-2">
                <a href="{{ route('verification.index') }}" class="btn btn-ns-primary w-100" style="background-color: var(--apple-danger); color: #fff;">
                    <i class="bi bi-shield-check"></i> Review Queue
                </a>
                <a href="{{ route('reports.index') }}" class="btn btn-outline-light w-100">
                    <i class="bi bi-graph-up"></i> Reports
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm animate-slide-up">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-journal-text text-apple-accent"></i> Recent System Activity Logs</span>
        <div class="d-flex align-items-center gap-2">
            <span class="badge border px-3 py-1" style="background-color: var(--apple-input-bg); color: var(--apple-text); border-color: var(--apple-border) !important; font-size: 0.75rem; font-weight: 500;"><i class="bi bi-clock-history me-1"></i>Last 10 Events</span>
            <a href="{{ route('logs.index') }}" class="btn btn-sm btn-outline-light px-3" style="font-size: 0.78rem;">
                <i class="bi bi-arrow-right"></i> View Full Activity Logs
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr class="border-bottom" style="border-color: var(--apple-border) !important;">
                        <th class="ps-4 py-3 fw-semibold small" style="color: var(--apple-text-muted);">Time</th>
                        <th class="py-3 fw-semibold small" style="color: var(--apple-text-muted);">User</th>
                        <th class="py-3 fw-semibold small" style="color: var(--apple-text-muted);">Action</th>
                        <th class="py-3 fw-semibold small" style="color: var(--apple-text-muted);">Description</th>
                        <th class="pe-4 py-3 fw-semibold small text-end" style="color: var(--apple-text-muted);">Level</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($recentLogs as $log)
                @php
                    $actionIcon = match(true) {
                        str_contains($log->action, 'user') => 'bi-person-check text-primary',
                        str_contains($log->action, 'donation') => 'bi-box-seam text-success',
                        str_contains($log->action, 'claim') => 'bi-hand-thumbs-up text-warning',
                        str_contains($log->action, 'inventory') => 'bi-building text-info',
                        default => 'bi-activity text-accent'
                    };
                @endphp
                <tr class="border-bottom" style="border-color: var(--apple-border) !important;">
                    <td class="ps-4 text-nowrap"><small style="color: var(--apple-text-muted);"><i class="bi bi-clock me-1"></i>{{ $log->created_at->format('d M H:i') }}</small></td>
                    <td>
                        <span class="fw-bold" style="color: var(--apple-text);">
                            <i class="bi {{ $actionIcon }} me-1"></i>
                            {{ $log->user?->name ?? 'System' }}
                        </span>
                    </td>
                    <td>
                        <span class="action-tag">
                            {{ $log->action }}
                        </span>
                    </td>
                    <td class="small" style="color: var(--apple-text-muted);">{{ Str::limit($log->description, 75) }}</td>
                    <td class="pe-4 text-end">
                        <span class="badge badge-{{ $log->level === 'error' ? 'danger' : ($log->level === 'warning' ? 'warning' : 'success') }}">
                            {{ strtoupper($log->level) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-4" style="color: var(--apple-text-muted);">No system logs recorded yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card shadow-sm animate-slide-up mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-basket text-apple-accent me-1"></i> Recent Available Donations</span>
        <div class="d-flex align-items-center gap-2">
            <span class="badge border px-3 py-1" style="background-color: var(--apple-input-bg); color: var(--apple-text); border-color: var(--apple-border) !important; font-size: 0.75rem; font-weight: 500;">
                <i class="bi bi-clock-history me-1"></i>Active Listings
            </span>
            <a href="{{ route('donations.index') }}" class="btn btn-sm btn-outline-light px-3" style="font-size: 0.78rem;">
                <i class="bi bi-arrow-right"></i> View All Donations
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr class="border-bottom" style="border-color: var(--apple-border) !important;">
                        <th class="ps-4 py-3 fw-semibold small" style="color: var(--apple-text-muted);">Donation Title</th>
                        <th class="py-3 fw-semibold small" style="color: var(--apple-text-muted);">Donor</th>
                        <th class="py-3 fw-semibold small" style="color: var(--apple-text-muted);">Quantity</th>
                        <th class="py-3 fw-semibold small" style="color: var(--apple-text-muted);">Status</th>
                        <th class="pe-4 py-3 fw-semibold small text-end" style="color: var(--apple-text-muted);">Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($recentDonations as $donation)
                <tr class="border-bottom" style="border-color: var(--apple-border) !important;">
                    <td class="ps-4">
                        <div class="d-flex align-items-center gap-3">
                            @if($donation->image_paths && count($donation->image_paths) > 0)
                                @php
                                    $thumbSrc = Str::startsWith($donation->image_paths[0], ['http://', 'https://']) ? $donation->image_paths[0] : asset('storage/' . $donation->image_paths[0]);
                                @endphp
                                <img src="{{ $thumbSrc }}" class="rounded shadow-sm" style="width: 36px; height: 36px; object-fit: cover;" alt="Thumbnail">
                            @else
                                <div class="rounded shadow-sm d-flex justify-content-center align-items-center" style="width: 36px; height: 36px; background: rgba(255,255,255,0.05);">
                                    <i class="bi bi-basket text-muted small"></i>
                                </div>
                            @endif
                            <strong style="color: var(--apple-text);">
                                <a href="{{ route('donations.show', $donation) }}" class="text-decoration-none" style="color: var(--apple-text);">{{ $donation->title }}</a>
                            </strong>
                        </div>
                    </td>
                    <td>
                        <strong style="color: var(--apple-text);">{{ $donation->donor->organization_name ?? $donation->donor->name }}</strong>
                        <br><small style="color: var(--apple-text-muted); font-size: 0.75rem;">{{ $donation->donor->email }}</small>
                    </td>
                    <td style="color: var(--apple-text);">
                        {{ $donation->quantity }} {{ $donation->unit }}
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
                    <td colspan="5" class="text-center py-5" style="color: var(--apple-text-muted);">No active available donations at the moment.</td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection
