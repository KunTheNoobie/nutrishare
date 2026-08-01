@extends('layouts.app')
@section('title', 'Notifications')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 animate-slide-up">
    <div>
        <h2 style="font-weight: 600;" class="mb-1"><i class="bi bi-bell text-apple-accent"></i> Notifications Center</h2>
        <p class="mb-0" style="color: var(--apple-text-muted);">View all system alerts, claim updates, and platform notifications.</p>
    </div>
    @if(Auth::user()->unreadNotificationsCount() > 0)
    <form method="POST" action="{{ route('notifications.read-all') }}">
        @csrf
        <button type="submit" class="btn btn-sm btn-outline-light">
            <i class="bi bi-check2-all me-1"></i> Mark All as Read
        </button>
    </form>
    @endif
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show animate-slide-up" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card shadow-sm animate-slide-up">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-inbox text-apple-accent me-1"></i> System Alerts List</span>
        <span class="badge border px-3 py-1" style="background-color: var(--apple-input-bg); color: var(--apple-text); border-color: var(--apple-border) !important; font-size: 0.75rem; font-weight: 500;">
            {{ $notifications->total() }} Total Notifications
        </span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr class="border-bottom" style="border-color: var(--apple-border) !important;">
                        <th class="ps-4 py-3 fw-semibold small" style="color: var(--apple-text-muted);">Alert</th>
                        <th class="py-3 fw-semibold small" style="color: var(--apple-text-muted);">Message</th>
                        <th class="py-3 fw-semibold small" style="color: var(--apple-text-muted);">Date & Time</th>
                        <th class="pe-4 py-3 fw-semibold small text-end" style="color: var(--apple-text-muted);">Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($notifications as $n)
                <tr class="border-bottom {{ !$n->is_read ? 'fw-bold' : '' }}" style="border-color: var(--apple-border) !important; {{ !$n->is_read ? 'background: rgba(41, 151, 255, 0.04);' : '' }}">
                    <td class="ps-4">
                        <div class="d-flex align-items-center gap-2">
                            @if(!$n->is_read)
                                <span class="badge bg-primary rounded-circle p-1" style="width: 8px; height: 8px;"></span>
                            @endif
                            <strong style="color: var(--apple-text);">{{ $n->title }}</strong>
                        </div>
                    </td>
                    <td style="color: var(--apple-text-muted); font-size: 0.9rem;">
                        {{ $n->message }}
                    </td>
                    <td class="small text-nowrap" style="color: var(--apple-text-muted);">
                        <i class="bi bi-clock me-1"></i>{{ $n->created_at->diffForHumans() }}
                    </td>
                    <td class="pe-4 text-end text-nowrap">
                        @if(!$n->is_read)
                        <form method="POST" action="{{ route('notifications.read', $n) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-light py-1 px-2" style="font-size: 0.75rem;">
                                <i class="bi bi-check me-1"></i> Mark Read
                            </button>
                        </form>
                        @else
                        <span class="badge badge-secondary" style="font-size: 0.72rem;">Read</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-5" style="color: var(--apple-text-muted);">
                        No notifications received yet.
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4 animate-slide-up">{{ $notifications->links() }}</div>
@endsection
