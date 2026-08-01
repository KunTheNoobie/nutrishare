@extends('layouts.app')
@section('title', 'Generate Report')

@section('content')
<div class="mb-4 animate-slide-up">
    <h2>
        <i class="bi bi-file-earmark-bar-graph text-apple-accent"></i> Generate New Report
    </h2>
    <p class="text-muted">Select a report type and generate platform analytics from live data.</p>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <form method="POST" action="{{ route('reports.store') }}">
            @csrf

            <div class="card shadow-sm mb-4 animate-slide-up">
                <div class="card-header"><i class="bi bi-gear"></i> Report Configuration</div>
                <div class="card-body p-4">

                    <div class="mb-4">
                        <label for="title" class="form-label">Report Title</label>
                        <input type="text" name="title" id="title"
                               class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title') }}" required
                               placeholder="e.g. Monthly SDG Impact Summary — July 2026">
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Report Type</label>
                        @error('type')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="radio" class="btn-check" name="type" id="type_sdg" value="sdg_impact"
                                   {{ old('type') === 'sdg_impact' ? 'checked' : '' }} required>
                            <label class="btn btn-outline-light w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4 text-center"
                                   for="type_sdg" style="min-height: 160px; border-radius: 12px;">
                                <i class="bi bi-globe text-apple-success" style="font-size: 2rem;"></i>
                                <strong class="d-block mt-2 mb-1">SDG Impact</strong>
                                <small class="text-muted">Beneficiaries fed, food saved, distribution locations</small>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <input type="radio" class="btn-check" name="type" id="type_donation" value="donation_summary"
                                   {{ old('type') === 'donation_summary' ? 'checked' : '' }}>
                            <label class="btn btn-outline-light w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4 text-center"
                                   for="type_donation" style="min-height: 160px; border-radius: 12px;">
                                <i class="bi bi-basket" style="font-size: 2rem; color: #ff9f0a;"></i>
                                <strong class="d-block mt-2 mb-1">Donation Summary</strong>
                                <small class="text-muted">Donations by status, top donors, top NGOs</small>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <input type="radio" class="btn-check" name="type" id="type_user" value="user_activity"
                                   {{ old('type') === 'user_activity' ? 'checked' : '' }}>
                            <label class="btn btn-outline-light w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4 text-center"
                                   for="type_user" style="min-height: 160px; border-radius: 12px;">
                                <i class="bi bi-people text-apple-accent" style="font-size: 2rem;"></i>
                                <strong class="d-block mt-2 mb-1">User Activity</strong>
                                <small class="text-muted">Users by role, verification stats, most active</small>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-ns-primary w-100 py-2 fw-medium animate-slide-up">
                <i class="bi bi-lightning-charge"></i> Generate Report
            </button>
        </form>
    </div>
</div>

@push('styles')
<style>
    .btn-check:checked + .btn-outline-light {
        background: rgba(10, 132, 255, 0.1) !important;
        border-color: var(--apple-accent) !important;
        color: var(--apple-text) !important;
    }
</style>
@endpush
@endsection
