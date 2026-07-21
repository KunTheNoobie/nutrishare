@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        {{-- SECURITY (Module 1): XSS Prevention — {{ }} escapes user-provided name --}}
        <h2>Welcome, {{ $user->name }}!</h2>
        <p class="text-muted mb-0">
            <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'ngo' ? 'info' : 'primary') }}">
                {{ ucfirst($user->role) }}
            </span>
            @if($user->isNgo())
                <span class="badge bg-{{ $user->isVerified() ? 'success' : 'warning' }}">
                    {{ $user->isVerified() ? 'Verified' : 'Pending Verification' }}
                </span>
            @endif
        </p>
    </div>
</div>

{{-- ── Donor Dashboard ── --}}
@if($user->isDonor())
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <h3 class="text-primary">{{ $totalDonations }}</h3>
                <p class="text-muted mb-0">Total Donations</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <h3 class="text-success">{{ $activeDonations }}</h3>
                <p class="text-muted mb-0">Active Donations</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <a href="{{ route('donations.create') }}" class="btn btn-ns-primary">
                    <i class="bi bi-plus-circle"></i> New Donation
                </a>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header"><i class="bi bi-gift"></i> Recent Donations</div>
    <div class="card-body">
        @forelse($donations as $donation)
        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
            <div>
                <strong>{{ $donation->title }}</strong>
                <br><small class="text-muted">{{ $donation->quantity }} {{ $donation->unit }} — Expires: {{ $donation->expiry_date->format('d M Y') }}</small>
            </div>
            <span class="badge bg-{{ $donation->status === 'available' ? 'success' : ($donation->status === 'claimed' ? 'warning' : 'secondary') }}">
                {{ ucfirst($donation->status) }}
            </span>
        </div>
        @empty
        <p class="text-muted">No donations yet. <a href="{{ route('donations.create') }}">Create your first donation!</a></p>
        @endforelse
    </div>
</div>
@endif

{{-- ── NGO Dashboard ── --}}
@if($user->isNgo())
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <h3 class="text-info">{{ $totalClaims }}</h3>
                <p class="text-muted mb-0">Total Claims</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <h3 class="text-warning">{{ $pendingClaims }}</h3>
                <p class="text-muted mb-0">Pending Claims</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <a href="{{ route('claims.browse') }}" class="btn btn-ns-primary">
                    <i class="bi bi-search"></i> Browse Donations
                </a>
            </div>
        </div>
    </div>
</div>

@if(!$user->isVerified())
<div class="card border-warning mb-4">
    <div class="card-body">
        <h5 class="text-warning"><i class="bi bi-exclamation-triangle"></i> Verification Required</h5>
        <p>Upload your NGO license to get verified by an admin.</p>
        <form method="POST" action="{{ route('verification.upload') }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-2">
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
                    <button type="submit" class="btn btn-warning w-100">Upload Document</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

<div class="card">
    <div class="card-header"><i class="bi bi-hand-thumbs-up"></i> Recent Claims</div>
    <div class="card-body">
        @forelse($claims as $claim)
        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
            <div>
                <strong>{{ $claim->donation->title }}</strong>
                <br><small class="text-muted">Claimed on {{ $claim->created_at->format('d M Y') }}</small>
            </div>
            <span class="badge bg-{{ $claim->status === 'approved' ? 'success' : ($claim->status === 'pending' ? 'warning' : 'secondary') }}">
                {{ ucfirst($claim->status) }}
            </span>
        </div>
        @empty
        <p class="text-muted">No claims yet. <a href="{{ route('claims.browse') }}">Browse available donations!</a></p>
        @endforelse
    </div>
</div>
@endif

{{-- ── Admin Dashboard ── --}}
@if($user->isAdmin())
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <h3 class="text-danger">{{ $pendingVerifications }}</h3>
                <p class="text-muted mb-0">Pending Verifications</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <h3 class="text-primary">{{ $totalUsers }}</h3>
                <p class="text-muted mb-0">Total Users</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <h3 class="text-success">{{ $totalDonations }}</h3>
                <p class="text-muted mb-0">Total Donations</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <a href="{{ route('verification.index') }}" class="btn btn-danger">
                    <i class="bi bi-shield-check"></i> Review Queue
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><i class="bi bi-journal-text"></i> Recent System Logs</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm">
                <thead><tr><th>Time</th><th>User</th><th>Action</th><th>Description</th><th>Level</th></tr></thead>
                <tbody>
                @forelse($recentLogs as $log)
                <tr>
                    <td><small>{{ $log->created_at->format('d M H:i') }}</small></td>
                    <td>{{ $log->user?->name ?? 'System' }}</td>
                    <td><code>{{ $log->action }}</code></td>
                    <td>{{ Str::limit($log->description, 60) }}</td>
                    <td><span class="badge bg-{{ $log->level === 'error' ? 'danger' : ($log->level === 'warning' ? 'warning' : 'info') }}">{{ $log->level }}</span></td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-muted">No system logs yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection
