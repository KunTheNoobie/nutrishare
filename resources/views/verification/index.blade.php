@extends('layouts.app')
@section('title', 'Verification Queue')

@section('content')
<h2 class="mb-4"><i class="bi bi-shield-check"></i> NGO Verification Queue</h2>

<div class="card">
    <div class="card-body">
        @forelse($documents as $doc)
        <div class="card mb-3 shadow-sm border" style="border-color: var(--apple-border) !important;">
            <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
                <div class="d-flex align-items-start gap-3">
                    @php
                        $ext = strtolower(pathinfo($doc->original_filename, PATHINFO_EXTENSION));
                        $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                    @endphp

                    @if($isImage)
                        <div class="position-relative border rounded p-1 shadow-sm bg-dark text-center" style="width: 90px; height: 90px; flex-shrink: 0; overflow: hidden;">
                            <img src="{{ route('verification.file', $doc) }}" class="rounded img-fluid h-100 w-100" style="object-fit: cover;" alt="Document Preview">
                        </div>
                    @else
                        <div class="border rounded p-3 text-center bg-secondary bg-opacity-10 d-flex flex-column align-items-center justify-content-center" style="width: 90px; height: 90px; flex-shrink: 0;">
                            <i class="bi bi-file-earmark-pdf fs-1 text-danger"></i>
                        </div>
                    @endif

                    <div class="flex-grow-1">
                        <h5 class="fw-bold text-light mb-1">{{ $doc->user->organization_name ?? $doc->user->name }}</h5>
                        <div class="mb-1 d-flex align-items-center gap-2 flex-wrap">
                            <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-30 px-2 py-1">
                                <i class="bi bi-file-text"></i> {{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}
                            </span>
                            <span class="text-muted small"><i class="bi bi-paperclip"></i> {{ $doc->original_filename }}</span>
                        </div>
                        <p class="mb-0 text-muted extra-small"><i class="bi bi-clock-history"></i> Uploaded: {{ $doc->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                </div>

                <div class="d-flex flex-column align-items-end gap-2 ms-auto mt-3 mt-md-0">
                    <form method="POST" action="{{ route('verification.review', $doc) }}" class="d-flex align-items-center gap-2">
                        @csrf
                        <input type="text" name="admin_remarks" class="form-control form-control-sm" placeholder="Remarks (optional)" style="width: 220px;">
                        <button type="submit" name="action" value="approved" class="btn btn-success btn-sm px-3 fw-medium text-nowrap">
                            <i class="bi bi-check-lg"></i> Approve
                        </button>
                        <button type="submit" name="action" value="rejected" class="btn btn-danger btn-sm px-3 fw-medium text-nowrap">
                            <i class="bi bi-x-lg"></i> Reject
                        </button>
                    </form>
                    <div class="d-flex gap-2">
                        <a href="{{ route('verification.file', $doc) }}" target="_blank" class="btn btn-sm btn-outline-info px-3">
                            <i class="bi bi-eye"></i> View File
                        </a>
                        <a href="{{ route('verification.download', $doc) }}" class="btn btn-sm btn-outline-primary px-3">
                            <i class="bi bi-download"></i> Download
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <p class="text-muted text-center py-4">No pending verifications. All clear! ✅</p>
        @endforelse
    </div>
</div>

<div class="mt-4">{{ $documents->links() }}</div>
@endsection
