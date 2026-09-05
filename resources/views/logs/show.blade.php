@extends('layouts.app')
@section('title', 'System Activity Log #' . $log->id)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 animate-slide-up">
    <a href="{{ route('logs.index') }}" class="btn btn-outline-light btn-sm px-3 d-inline-flex align-items-center gap-2">
        <i class="bi bi-arrow-left"></i> <span>Back to System Logs</span>
    </a>
    <span class="badge badge-{{ $log->level === 'error' ? 'danger' : ($log->level === 'warning' ? 'warning' : 'success') }} px-3 py-2 d-inline-flex align-items-center gap-1">
        <span class="pulse-dot pulse-dot-{{ $log->level === 'error' ? 'danger' : ($log->level === 'warning' ? 'warning' : 'success') }}"></span>
        {{ strtoupper($log->level) }} AUDIT RECORD
    </span>
</div>

<div class="card shadow-sm animate-slide-up">
    <div class="card-header d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-bold d-inline-flex align-items-center gap-2" style="color: var(--apple-text);">
            <i class="bi bi-shield-shaded text-apple-accent fs-5"></i>
            <span>System Activity Log #{{ $log->id }}</span>
        </h5>
        <span class="badge border px-3 py-2 font-monospace" style="background: rgba(41, 151, 255, 0.12); color: var(--apple-accent); border-color: rgba(41, 151, 255, 0.35) !important; font-size: 0.85rem;">
            <i class="bi bi-terminal me-1"></i>{{ $log->action }}
        </span>
    </div>
    <div class="card-body p-4">
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="p-3 rounded border h-100" style="background: var(--apple-surface); border-color: var(--apple-border) !important;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: rgba(41, 151, 255, 0.12); color: var(--apple-accent);">
                            <i class="bi bi-clock-history fs-5"></i>
                        </div>
                        <div class="flex-grow-1">
                            <small style="color: var(--apple-text-muted);" class="d-block mb-1 text-uppercase fw-semibold" style="font-size: 0.72rem;">Timestamp</small>
                            <div class="fw-bold" style="color: var(--apple-text); font-size: 1.05rem;">
                                <span data-date="{{ $log->created_at->toIso8601String() }}" data-with-time>{{ $log->created_at->format('d M Y, h:i:s A') }}</span>
                                <small class="opacity-75 text-muted ms-1">({{ $log->created_at->diffForHumans() }})</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 rounded border h-100" style="background: var(--apple-surface); border-color: var(--apple-border) !important;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: rgba(52, 199, 89, 0.12); color: var(--apple-success);">
                            <i class="bi bi-person-badge fs-5"></i>
                        </div>
                        <div class="flex-grow-1">
                            <small style="color: var(--apple-text-muted);" class="d-block mb-1 text-uppercase fw-semibold" style="font-size: 0.72rem;">Action Performer</small>
                            <div class="fw-bold d-flex align-items-center gap-2" style="color: var(--apple-text); font-size: 1.05rem;">
                                <span>{{ $log->user?->name ?? 'System Automated Service' }}</span>
                                @if($log->user)
                                    <span class="badge badge-{{ $log->user->role === 'admin' ? 'admin' : ($log->user->role === 'moderator' ? 'moderator' : ($log->user->role === 'ngo' ? 'ngo' : 'donor')) }}" style="font-size: 0.7rem;">
                                        {{ ucfirst($log->user->role) }}
                                    </span>
                                @endif
                            </div>
                            @if($log->user)
                            <div class="small mt-1 text-muted" style="font-size: 0.78rem;">
                                <i class="bi bi-envelope me-1"></i>{{ $log->user->email }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="fw-bold mb-0" style="color: var(--apple-text);">
                    <i class="bi bi-file-code me-2 text-apple-accent"></i> Full Description / Event Log Payload
                </h6>
                <button type="button" class="btn btn-sm btn-outline-secondary px-2 py-1" onclick="navigator.clipboard.writeText(document.getElementById('logPayload').innerText); this.innerHTML='<i class=\'bi bi-check2\'></i> Copied'; setTimeout(() => this.innerHTML='<i class=\'bi bi-clipboard\'></i> Copy', 2000);" style="font-size: 0.75rem; height: 28px !important; min-height: 28px !important;">
                    <i class="bi bi-clipboard"></i> Copy
                </button>
            </div>
            <div id="logPayload" class="p-3 rounded border font-monospace" style="background: var(--apple-input-bg); color: var(--apple-text); border-color: var(--apple-border) !important; white-space: pre-wrap; word-break: break-word; font-size: 0.92rem; line-height: 1.5;">{{ $log->description }}</div>
        </div>

        @if($log->ip_address || $log->user_agent)
        <div class="row g-3 pt-2">
            <div class="col-md-4">
                <div class="p-3 rounded border h-100" style="background: var(--apple-surface); border-color: var(--apple-border) !important;">
                    <small style="color: var(--apple-text-muted);" class="d-block mb-1 text-uppercase fw-semibold" style="font-size: 0.72rem;">
                        <i class="bi bi-hdd-network me-1 text-apple-accent"></i> IP Address
                    </small>
                    <span class="font-monospace fw-bold" style="color: var(--apple-text); font-size: 0.95rem;">{{ $log->ip_address ?? '127.0.0.1' }}</span>
                </div>
            </div>
            <div class="col-md-8">
                <div class="p-3 rounded border h-100" style="background: var(--apple-surface); border-color: var(--apple-border) !important;">
                    <small style="color: var(--apple-text-muted);" class="d-block mb-1 text-uppercase fw-semibold" style="font-size: 0.72rem;">
                        <i class="bi bi-laptop me-1 text-apple-accent"></i> User Agent / Environment
                    </small>
                    <span class="font-monospace small text-break d-block" style="color: var(--apple-text-muted); font-size: 0.78rem; line-height: 1.4;">{{ $log->user_agent ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
