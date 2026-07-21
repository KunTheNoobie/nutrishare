@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 animate-slide-up">
    <div>
        {{-- SECURITY (Module 1): XSS Prevention — {{ }} escapes user-provided name --}}
        <h2>Welcome, {{ $user->name }}!</h2>
        <p class="text-muted mb-0">
            <span class="badge badge-{{ $user->role === 'admin' ? 'admin' : ($user->role === 'ngo' ? 'ngo' : 'donor') }}">
                {{ ucfirst($user->role) }}
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
    <div class="card-header"><i class="bi bi-gift text-apple-accent"></i> Recent Donations</div>
    <div class="card-body">
        @forelse($donations as $donation)
        <div class="d-flex justify-content-between align-items-center py-3 border-bottom border-dark">
            <div>
                <strong style="font-size: 1.05rem;">{{ $donation->title }}</strong>
                <br><small class="text-muted">{{ $donation->quantity }} {{ $donation->unit }} &nbsp;&middot;&nbsp; Expires: {{ $donation->expiry_date->format('d M Y') }}</small>
            </div>
            <span class="badge badge-{{ $donation->status === 'available' ? 'success' : ($donation->status === 'claimed' ? 'warning' : 'secondary') }}">
                {{ ucfirst($donation->status) }}
            </span>
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
                <a href="{{ route('claims.browse') }}" class="btn btn-ns-primary w-100">
                    <i class="bi bi-search"></i> Browse Donations
                </a>
            </div>
        </div>
    </div>
</div>

@if(!$user->isVerified())
<div class="card border-warning mb-4 animate-slide-up" style="border: 1px solid rgba(255, 149, 0, 0.4);">
    <div class="card-body p-4">
        <h5 class="text-apple-warning mb-3"><i class="bi bi-exclamation-triangle"></i> Verification Required</h5>
        <p class="text-muted">Upload your NGO license to get verified by an admin.</p>
        <form method="POST" action="{{ route('verification.upload') }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-3 align-items-center">
                <div class="col-md-4">
                    <select name="document_type" class="form-select" required>
                        <option value="license">NGO License</option>
                        <option value="registration_cert">Registration Certificate</option>
                        <option value="tax_exempt">Tax Exemption Letter</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <input type="file" name="document" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-ns-primary w-100" style="background-color: #ff9f0a; color: #111;">Upload</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

<div class="card shadow-sm animate-slide-up">
    <div class="card-header"><i class="bi bi-hand-thumbs-up text-apple-success"></i> Recent Claims</div>
    <div class="card-body">
        @forelse($claims as $claim)
        <div class="d-flex justify-content-between align-items-center py-3 border-bottom border-dark">
            <div>
                <strong style="font-size: 1.05rem;">{{ $claim->donation->title }}</strong>
                <br><small class="text-muted">Claimed on {{ $claim->created_at->format('d M Y') }}</small>
            </div>
            <span class="badge badge-{{ $claim->status === 'approved' ? 'success' : ($claim->status === 'pending' ? 'warning' : 'secondary') }}">
                {{ ucfirst($claim->status) }}
            </span>
        </div>
        @empty
        <p class="text-muted text-center py-4">No claims yet. <a href="{{ route('claims.browse') }}">Browse available donations!</a></p>
        @endforelse
    </div>
</div>
@endif

{{-- ── Admin Dashboard ── --}}
@if($user->isAdmin())
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
            <div class="card-body text-center py-4 w-100 d-flex flex-column justify-content-center">
                <a href="{{ route('verification.index') }}" class="btn btn-ns-primary w-100" style="background-color: var(--apple-danger); color: #fff;">
                    <i class="bi bi-shield-check"></i> Review Queue
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm animate-slide-up">
    <div class="card-header"><i class="bi bi-journal-text text-apple-accent"></i> Recent System Logs</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-dark table-hover table-sm border-dark">
                <thead><tr><th class="text-muted fw-normal">Time</th><th class="text-muted fw-normal">User</th><th class="text-muted fw-normal">Action</th><th class="text-muted fw-normal">Description</th><th class="text-muted fw-normal">Level</th></tr></thead>
                <tbody>
                @forelse($recentLogs as $log)
                <tr>
                    <td><small class="text-muted">{{ $log->created_at->format('d M H:i') }}</small></td>
                    <td>{{ $log->user?->name ?? 'System' }}</td>
                    <td><code class="text-apple-accent" style="background: transparent;">{{ $log->action }}</code></td>
                    <td class="text-muted">{{ Str::limit($log->description, 60) }}</td>
                    <td><span class="badge badge-{{ $log->level === 'error' ? 'danger' : ($log->level === 'warning' ? 'warning' : 'success') }}">{{ $log->level }}</span></td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-muted text-center py-4">No system logs yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection
