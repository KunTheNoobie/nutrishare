@extends('layouts.app')
@section('title', 'My Claims')

@section('content')
<h2 class="mb-4"><i class="bi bi-hand-thumbs-up"></i> My Claims</h2>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr><th>Donation</th><th>Status</th><th>Pickup Date</th><th>Vehicle</th><th>Receipt</th><th>Actions</th></tr>
                </thead>
                <tbody>
                @forelse($claims as $claim)
                <tr>
                    <td>
                        <strong>{{ $claim->donation->title }}</strong>
                        <br><small class="text-muted">{{ $claim->donation->quantity }} {{ $claim->donation->unit }}</small>
                    </td>
                    <td>
                        <span class="badge bg-{{ $claim->status === 'approved' ? 'success' : ($claim->status === 'pending' ? 'warning' : ($claim->status === 'collected' ? 'info' : 'secondary')) }}">
                            {{ ucfirst($claim->status) }}
                        </span>
                    </td>
                    <td>{{ $claim->pickup_scheduled_at?->format('d M Y') ?? 'TBD' }}</td>
                    <td>{{ $claim->vehicle ? $claim->vehicle->plate_number : '—' }}</td>
                    <td>{{ $claim->collectionReceipt ? $claim->collectionReceipt->receipt_number : '—' }}</td>
                    <td><a href="{{ route('claims.show', $claim) }}" class="btn btn-sm btn-outline-primary">View</a></td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-muted text-center">No claims yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">{{ $claims->links() }}</div>
@endsection
