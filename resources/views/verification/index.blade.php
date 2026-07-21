@extends('layouts.app')
@section('title', 'Verification Queue')

@section('content')
<h2 class="mb-4"><i class="bi bi-shield-check"></i> NGO Verification Queue</h2>

<div class="card">
    <div class="card-body">
        @forelse($documents as $doc)
        <div class="card mb-3">
            <div class="card-body d-flex justify-content-between align-items-start">
                <div>
                    <h5>{{ $doc->user->organization_name ?? $doc->user->name }}</h5>
                    <p class="mb-1"><strong>Document Type:</strong> {{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}</p>
                    <p class="mb-1"><strong>File:</strong> {{ $doc->original_filename }}</p>
                    <p class="mb-0"><small class="text-muted">Uploaded: {{ $doc->created_at->format('d M Y, h:i A') }}</small></p>
                </div>
                <div>
                    <form method="POST" action="{{ route('verification.review', $doc) }}" class="d-inline">
                        @csrf
                        <input type="hidden" name="action" value="approved">
                        <input type="text" name="admin_remarks" class="form-control form-control-sm mb-2" placeholder="Remarks (optional)">
                        <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-check"></i> Approve</button>
                    </form>
                    <form method="POST" action="{{ route('verification.review', $doc) }}" class="d-inline ms-2">
                        @csrf
                        <input type="hidden" name="action" value="rejected">
                        <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-x"></i> Reject</button>
                    </form>
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
