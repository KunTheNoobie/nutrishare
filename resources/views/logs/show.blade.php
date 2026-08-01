@extends('layouts.app')
@section('title', 'Log Event Details #' . $log->id)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 animate-slide-up">
    <a href="{{ route('logs.index') }}" class="btn btn-outline-light btn-sm px-3">
        <i class="bi bi-arrow-left"></i> Back to System Logs
    </a>
    <span class="badge badge-{{ $log->level === 'error' ? 'danger' : ($log->level === 'warning' ? 'warning' : 'success') }} fs-6 px-3 py-2">
        {{ strtoupper($log->level) }} LEVEL
    </span>
</div>

<div class="card shadow-sm animate-slide-up">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0 fw-bold" style="color: var(--apple-text);">
            <i class="bi bi-journal-text text-apple-accent me-2"></i> System Activity Log #{{ $log->id }}
        </h4>
        <span class="action-tag">{{ $log->action }}</span>
    </div>
    <div class="card-body p-4">
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="p-3 rounded border" style="background: var(--apple-surface); border-color: var(--apple-border) !important;">
                    <small style="color: var(--apple-text-muted);" class="d-block mb-1 text-uppercase fw-semibold">Timestamp</small>
                    <div class="fw-bold" style="color: var(--apple-text); font-size: 1.1rem;">
                        <i class="bi bi-calendar-event me-2 text-apple-accent"></i>{{ $log->created_at->format('d M Y, h:i:s A') }}
                        <small class="opacity-75 font-monospace text-muted">({{ $log->created_at->diffForHumans() }})</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 rounded border" style="background: var(--apple-surface); border-color: var(--apple-border) !important;">
                    <small style="color: var(--apple-text-muted);" class="d-block mb-1 text-uppercase fw-semibold">Action Performer</small>
                    <div class="fw-bold" style="color: var(--apple-text); font-size: 1.1rem;">
                        <i class="bi bi-person-circle me-2 text-apple-accent"></i>{{ $log->user?->name ?? 'System Automated Service' }}
                    </div>
                    @if($log->user)
                    <div class="small mt-1" style="color: var(--apple-text-muted);">
                        <strong>Email:</strong> {{ $log->user->email }} &nbsp;&middot;&nbsp; 
                        <strong>Role:</strong> <span class="badge bg-secondary bg-opacity-20 text-light">{{ ucfirst($log->user->role) }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="mb-4">
            <h6 class="fw-bold mb-2" style="color: var(--apple-text);"><i class="bi bi-card-text me-2 text-apple-accent"></i> Full Description / Event Log Payload</h6>
            <div class="p-3 rounded border font-monospace" style="background: var(--apple-input-bg); color: var(--apple-text); border-color: var(--apple-border) !important; white-space: pre-wrap; word-break: break-word; font-size: 0.95rem;">{{ $log->description }}</div>
        </div>

        @if($log->ip_address || $log->user_agent)
        <div class="row g-3 pt-3 border-top" style="border-color: var(--apple-border) !important;">
            <div class="col-md-4">
                <small style="color: var(--apple-text-muted);" class="d-block">IP Address</small>
                <span class="font-monospace" style="color: var(--apple-text);">{{ $log->ip_address ?? 'N/A' }}</span>
            </div>
            <div class="col-md-8">
                <small style="color: var(--apple-text-muted);" class="d-block">User Agent / Environment</small>
                <span class="font-monospace small text-break" style="color: var(--apple-text-muted);">{{ $log->user_agent ?? 'N/A' }}</span>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
