@extends('layouts.app')
@section('title', $report->title)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 animate-slide-up">
    <div>
        <h2 class="mb-1">
            <i class="bi bi-file-earmark-bar-graph text-apple-accent"></i> {{ $report->title }}
        </h2>
        <p class="text-muted mb-0">
            <span class="badge badge-{{ $report->type === 'sdg_impact' ? 'success' : ($report->type === 'donation_summary' ? 'warning' : 'primary') }}">
                {{ $report->type === 'sdg_impact' ? 'SDG Impact' : ($report->type === 'donation_summary' ? 'Donation Summary' : 'User Activity') }}
            </span>
            &nbsp;&middot;&nbsp;
            <i class="bi bi-person"></i> {{ $report->user->name ?? 'System' }}
            &nbsp;&middot;&nbsp;
            <i class="bi bi-calendar"></i> {{ $report->report_date->format('d M Y, h:i A') }}
        </p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <form method="POST" action="{{ route('reports.refresh', $report) }}">
            @csrf
            <button type="submit" class="btn btn-ns-accent btn-sm text-nowrap">
                <i class="bi bi-arrow-clockwise"></i> Refresh Live Data
            </button>
        </form>
        <a href="{{ route('reports.index') }}" class="btn btn-outline-light btn-sm text-nowrap">
            <i class="bi bi-arrow-left"></i> All Reports
        </a>
    </div>
</div>

{{-- ─── SDG Impact Report ─── --}}
@if($report->type === 'sdg_impact')
<div class="row g-3 mb-4 animate-slide-up">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center py-4">
                <h2 class="text-apple-success mb-2" style="font-size: 2.5rem; font-weight: 600;">{{ number_format($content['total_beneficiaries'] ?? 0) }}</h2>
                <p class="text-muted mb-0"><i class="bi bi-people"></i> People Fed</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center py-4">
                <h2 class="text-apple-accent mb-2" style="font-size: 2.5rem; font-weight: 600;">{{ number_format($content['total_food_saved_kg'] ?? 0, 1) }}</h2>
                <p class="text-muted mb-0"><i class="bi bi-box-seam"></i> Food Saved (qty)</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center py-4">
                <h2 class="mb-2" style="font-size: 2.5rem; font-weight: 600; color: #bf5af2;">{{ number_format($content['total_distributions'] ?? 0) }}</h2>
                <p class="text-muted mb-0"><i class="bi bi-truck"></i> Distributions Made</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4 animate-slide-up">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body text-center py-4">
                <h3 class="text-apple-success mb-2" style="font-weight: 600;">{{ $content['total_donations_completed'] ?? 0 }}</h3>
                <p class="text-muted mb-0">Donations Completed</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body text-center py-4">
                <h3 class="text-apple-accent mb-2" style="font-weight: 600;">{{ $content['total_donations_collected'] ?? 0 }}</h3>
                <p class="text-muted mb-0">Donations Collected</p>
            </div>
        </div>
    </div>
</div>

@if(!empty($content['top_distribution_locations']))
<div class="card shadow-sm animate-slide-up mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-geo-alt text-apple-accent"></i> Top Distribution Locations</span>
        <span class="badge border px-3 py-1" style="background-color: var(--apple-input-bg); color: var(--apple-text); border-color: var(--apple-border) !important; font-size: 0.75rem; font-weight: 500;">
            {{ count($content['top_distribution_locations']) }} Locations
        </span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr class="border-bottom" style="border-color: var(--apple-border) !important;">
                        <th class="ps-4 py-3 fw-semibold small" style="color: var(--apple-text-muted);">Location</th>
                        <th class="py-3 fw-semibold small" style="color: var(--apple-text-muted);">Beneficiaries</th>
                        <th class="py-3 fw-semibold small" style="color: var(--apple-text-muted);">Qty Distributed</th>
                        <th class="pe-4 py-3 fw-semibold small text-end" style="color: var(--apple-text-muted);">Times</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($content['top_distribution_locations'] as $loc)
                <tr class="border-bottom" style="border-color: var(--apple-border) !important;">
                    <td class="ps-4"><strong style="color: var(--apple-text);">{{ $loc['distribution_location'] }}</strong></td>
                    <td><span class="text-apple-success fw-bold">{{ number_format($loc['total_beneficiaries']) }}</span></td>
                    <td style="color: var(--apple-text);">{{ number_format($loc['total_quantity'], 1) }}</td>
                    <td class="pe-4 text-end" style="color: var(--apple-text);">{{ $loc['distribution_count'] }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endif

{{-- ─── Donation Summary Report ─── --}}
@if($report->type === 'donation_summary')
<div class="row g-3 mb-4 animate-slide-up">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body text-center py-4">
                <h2 class="text-apple-accent mb-2" style="font-size: 2.5rem; font-weight: 600;">{{ $content['total_donations'] ?? 0 }}</h2>
                <p class="text-muted mb-0"><i class="bi bi-basket"></i> Total Donations</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body text-center py-4">
                <h2 class="text-apple-success mb-2" style="font-size: 2.5rem; font-weight: 600;">{{ $content['total_claims'] ?? 0 }}</h2>
                <p class="text-muted mb-0"><i class="bi bi-hand-thumbs-up"></i> Total Claims</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4 animate-slide-up">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header"><i class="bi bi-pie-chart text-apple-accent"></i> Donations by Status</div>
            <div class="card-body">
                @foreach(($content['donations_by_status'] ?? []) as $status => $count)
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom" style="border-color: var(--apple-border) !important;">
                    <span class="badge badge-{{ $status === 'available' ? 'success' : ($status === 'claimed' ? 'warning' : ($status === 'expired' ? 'danger' : 'secondary')) }}">
                        {{ ucfirst($status) }}
                    </span>
                    <strong class="text-light">{{ $count }}</strong>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header"><i class="bi bi-pie-chart text-apple-success"></i> Claims by Status</div>
            <div class="card-body">
                @foreach(($content['claims_by_status'] ?? []) as $status => $count)
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom" style="border-color: var(--apple-border) !important;">
                    <span class="badge badge-{{ $status === 'approved' ? 'success' : ($status === 'pending' ? 'warning' : ($status === 'rejected' ? 'danger' : 'secondary')) }}">
                        {{ ucfirst($status) }}
                    </span>
                    <strong class="text-light">{{ $count }}</strong>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4 animate-slide-up">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header"><i class="bi bi-trophy text-apple-accent"></i> Top Donors</div>
            <div class="card-body">
                @foreach(($content['top_donors'] ?? []) as $i => $donor)
                <div class="d-flex justify-content-between align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}" style="border-color: var(--apple-border) !important;">
                    <span><span class="text-muted me-2">{{ $i + 1 }}.</span> {{ $donor['name'] }}</span>
                    <span class="badge badge-success">{{ $donor['donations_count'] }} donations</span>
                </div>
                @endforeach
                @if(empty($content['top_donors']))
                <p class="text-muted text-center mb-0 py-3">No donors yet.</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header"><i class="bi bi-building text-apple-success"></i> Top NGOs by Claims</div>
            <div class="card-body">
                @foreach(($content['top_ngos'] ?? []) as $i => $ngo)
                <div class="d-flex justify-content-between align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}" style="border-color: var(--apple-border) !important;">
                    <span><span class="text-muted me-2">{{ $i + 1 }}.</span> {{ $ngo['name'] }}</span>
                    <span class="badge badge-warning">{{ $ngo['claims_count'] }} claims</span>
                </div>
                @endforeach
                @if(empty($content['top_ngos']))
                <p class="text-muted text-center mb-0 py-3">No NGOs yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

{{-- ─── User Activity Report ─── --}}
@if($report->type === 'user_activity')
<div class="row g-3 mb-4 animate-slide-up">
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body text-center py-4">
                <h2 class="text-apple-accent mb-2" style="font-size: 2.5rem; font-weight: 600;">{{ $content['total_users'] ?? 0 }}</h2>
                <p class="text-muted mb-0">Total Users</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body text-center py-4">
                <h2 class="text-apple-success mb-2" style="font-size: 2.5rem; font-weight: 600;">{{ $content['verified_ngos'] ?? 0 }}</h2>
                <p class="text-muted mb-0">Verified NGOs</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body text-center py-4">
                <h2 class="mb-2" style="font-size: 2.5rem; font-weight: 600; color: #ff9f0a;">{{ $content['pending_ngos'] ?? 0 }}</h2>
                <p class="text-muted mb-0">Pending NGOs</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body text-center py-4">
                @php $roles = $content['users_by_role'] ?? []; @endphp
                <h2 class="mb-2" style="font-size: 2.5rem; font-weight: 600; color: #bf5af2;">{{ count($roles) }}</h2>
                <p class="text-muted mb-0">Roles Active</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4 animate-slide-up">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header"><i class="bi bi-diagram-3 text-apple-accent"></i> Users by Role</div>
            <div class="card-body">
                @foreach(($content['users_by_role'] ?? []) as $role => $count)
                <div class="d-flex justify-content-between align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}" style="border-color: var(--apple-border) !important;">
                    <span class="badge badge-{{ $role === 'admin' ? 'primary' : ($role === 'ngo' ? 'warning' : ($role === 'moderator' ? 'moderator' : 'success')) }}">
                        {{ $role === 'ngo' ? 'NGO' : ucfirst($role) }}
                    </span>
                    <strong class="text-light">{{ $count }}</strong>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-lightning text-apple-accent"></i> Most Active Users</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr class="border-bottom" style="border-color: var(--apple-border) !important;">
                                <th class="ps-4 py-3 fw-semibold small" style="color: var(--apple-text-muted);">User</th>
                                <th class="py-3 fw-semibold small" style="color: var(--apple-text-muted);">Role</th>
                                <th class="py-3 fw-semibold small" style="color: var(--apple-text-muted);">Donations</th>
                                <th class="pe-4 py-3 fw-semibold small text-end" style="color: var(--apple-text-muted);">Claims</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach(($content['most_active_users'] ?? []) as $u)
                        <tr class="border-bottom" style="border-color: var(--apple-border) !important;">
                            <td class="ps-4"><strong style="color: var(--apple-text);">{{ $u['name'] }}</strong></td>
                            <td><span class="badge badge-{{ $u['role'] === 'admin' ? 'primary' : ($u['role'] === 'ngo' ? 'warning' : ($u['role'] === 'moderator' ? 'moderator' : 'success')) }}">{{ $u['role'] === 'ngo' ? 'NGO' : ucfirst($u['role']) }}</span></td>
                            <td style="color: var(--apple-text);">{{ $u['donations'] }}</td>
                            <td class="pe-4 text-end" style="color: var(--apple-text);">{{ $u['claims'] }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm animate-slide-up">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-clock-history text-apple-accent"></i> Recently Joined Users</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr class="border-bottom" style="border-color: var(--apple-border) !important;">
                        <th class="ps-4 py-3 fw-semibold small" style="color: var(--apple-text-muted);">Name</th>
                        <th class="py-3 fw-semibold small" style="color: var(--apple-text-muted);">Role</th>
                        <th class="pe-4 py-3 fw-semibold small text-end" style="color: var(--apple-text-muted);">Joined</th>
                    </tr>
                </thead>
                <tbody>
                @foreach(($content['recent_users'] ?? []) as $u)
                <tr class="border-bottom" style="border-color: var(--apple-border) !important;">
                    <td class="ps-4"><strong style="color: var(--apple-text);">{{ $u['name'] }}</strong></td>
                    <td><span class="badge badge-{{ $u['role'] === 'admin' ? 'primary' : ($u['role'] === 'ngo' ? 'warning' : ($u['role'] === 'moderator' ? 'moderator' : 'success')) }}">{{ $u['role'] === 'ngo' ? 'NGO' : ucfirst($u['role']) }}</span></td>
                    <td class="pe-4 text-end small" style="color: var(--apple-text-muted);"><i class="bi bi-calendar me-1"></i>{{ $u['joined'] }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@endsection
