@extends('layouts.app')
@section('title', 'System Activity Logs')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 animate-slide-up">
    <div>
        <h2 style="font-weight: 600;" class="mb-1"><i class="bi bi-journal-text text-apple-accent"></i> System Activity Logs</h2>
        <p class="mb-0" style="color: var(--apple-text-muted);">Complete platform audit trail and security event logs.</p>
    </div>
    <span class="badge border px-3 py-2" style="background-color: var(--apple-input-bg); color: var(--apple-text); border-color: var(--apple-border) !important; font-size: 0.85rem; font-weight: 500;">
        <i class="bi bi-shield-check me-1 text-apple-success"></i> Audit Active
    </span>
</div>

<!-- Search & Filter Controls -->
<div class="card shadow-sm mb-4 animate-slide-up">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('logs.index') }}" class="row g-2 align-items-center m-0">
            <div class="col-md-5 position-relative">
                <i class="bi bi-search text-apple-accent position-absolute" style="left: 15px; top: 50%; transform: translateY(-50%);"></i>
                <input type="text" name="search" class="form-control border-0 bg-transparent" placeholder="Search by user, action, description..."
                       value="{{ request('search') }}" style="box-shadow: none; padding-left: 40px;">
            </div>
            <div class="col-md-3">
                <select name="level" class="form-select border-0 bg-transparent" style="box-shadow: none; border-left: 1px solid var(--apple-border) !important; border-radius: 0;">
                    <option value="all">All Levels</option>
                    <option value="info" {{ request('level') === 'info' ? 'selected' : '' }}>Info</option>
                    <option value="warning" {{ request('level') === 'warning' ? 'selected' : '' }}>Warning</option>
                    <option value="error" {{ request('level') === 'error' ? 'selected' : '' }}>Error</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="action" class="form-select border-0 bg-transparent" style="box-shadow: none; border-left: 1px solid var(--apple-border) !important; border-radius: 0;">
                    <option value="all">All Action Types</option>
                    @foreach($actions as $act)
                        <option value="{{ $act }}" {{ request('action') === $act ? 'selected' : '' }}>{{ $act }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1 text-end">
                <button type="submit" class="btn btn-ns-accent btn-sm w-100">
                    <i class="bi bi-funnel"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Logs Table -->
<div class="card shadow-sm animate-slide-up">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr class="border-bottom" style="border-color: var(--apple-border) !important;">
                        <th class="ps-4 py-3 fw-semibold small" style="color: var(--apple-text-muted);">Time</th>
                        <th class="py-3 fw-semibold small" style="color: var(--apple-text-muted);">Performer</th>
                        <th class="py-3 fw-semibold small" style="color: var(--apple-text-muted);">Action</th>
                        <th class="py-3 fw-semibold small" style="color: var(--apple-text-muted);">Description</th>
                        <th class="py-3 fw-semibold small" style="color: var(--apple-text-muted);">Level</th>
                        <th class="pe-4 py-3 fw-semibold small text-end" style="color: var(--apple-text-muted);">Details</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($logs as $log)
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
                    <td class="ps-4 text-nowrap"><small style="color: var(--apple-text-muted);"><i class="bi bi-clock me-1"></i>{{ $log->created_at->format('d M Y, H:i') }}</small></td>
                    <td>
                        <span class="fw-bold" style="color: var(--apple-text);">
                            <i class="bi {{ $actionIcon }} me-1"></i>
                            {{ $log->user?->organization_name ?? $log->user?->name ?? 'System' }}
                        </span>
                        @if($log->user)
                            <br><small style="color: var(--apple-text-muted); font-size: 0.75rem;">{{ $log->user->email }} ({{ ucfirst($log->user->role) }})</small>
                        @endif
                    </td>
                    <td>
                        <span class="action-tag">
                            {{ $log->action }}
                        </span>
                    </td>
                    <td class="small" style="color: var(--apple-text-muted); max-width: 300px;">{{ Str::limit($log->description, 90) }}</td>
                    <td>
                        <span class="badge badge-{{ $log->level === 'error' ? 'danger' : ($log->level === 'warning' ? 'warning' : 'success') }}">
                            {{ strtoupper($log->level) }}
                        </span>
                    </td>
                    <td class="pe-4 text-end">
                        <a href="{{ route('logs.show', $log) }}" class="btn btn-sm btn-outline-light text-nowrap px-3">
                            <i class="bi bi-eye"></i> Details
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-5" style="color: var(--apple-text-muted);">No system logs match your filters.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4 animate-slide-up">{{ $logs->links() }}</div>
@endsection
