@extends('layouts.app')
@section('title', 'Platform Reports')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 animate-slide-up">
    <h2>
        <i class="bi bi-graph-up text-apple-accent"></i> Platform Reports
    </h2>
    @if(Auth::user()->isAdmin())
    <a href="{{ route('reports.create') }}" class="btn btn-ns-primary">
        <i class="bi bi-plus-circle"></i> Generate Report
    </a>
    @endif
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show animate-slide-up" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row g-4">
    @forelse($reports as $report)
    <div class="col-md-6 animate-slide-up" style="animation-delay: {{ $loop->index * 0.05 }}s;">
        <div class="card shadow-sm h-100">
            <div class="card-body d-flex flex-column p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="card-title fw-bold mb-1 text-light">{{ $report->title }}</h5>
                        <small class="text-muted">
                            <i class="bi bi-person"></i> {{ $report->user->name ?? 'System' }}
                            &nbsp;&middot;&nbsp;
                            <i class="bi bi-calendar"></i> {{ $report->report_date->format('d M Y') }}
                        </small>
                    </div>
                    <span class="badge badge-{{ $report->type === 'sdg_impact' ? 'success' : ($report->type === 'donation_summary' ? 'warning' : 'primary') }}">
                        @if($report->type === 'sdg_impact')
                            <i class="bi bi-globe"></i> SDG Impact
                        @elseif($report->type === 'donation_summary')
                            <i class="bi bi-basket"></i> Donations
                        @else
                            <i class="bi bi-people"></i> Users
                        @endif
                    </span>
                </div>

                <div class="mt-auto d-flex gap-2">
                    <a href="{{ route('reports.show', $report) }}" class="btn btn-sm btn-outline-light flex-grow-1">
                        <i class="bi bi-eye"></i> View Report
                    </a>
                    @if(Auth::user()->isAdmin())
                    <form method="POST" action="{{ route('reports.destroy', $report) }}" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                onclick="return confirm('Delete this report?')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-graph-up text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-3 mb-0">No reports generated yet.</p>
                @if(Auth::user()->isAdmin())
                <a href="{{ route('reports.create') }}" class="btn btn-ns-primary mt-3">
                    <i class="bi bi-plus-circle"></i> Generate Your First Report
                </a>
                @endif
            </div>
        </div>
    </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $reports->links() }}
</div>
@endsection
