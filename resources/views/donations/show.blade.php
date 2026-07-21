@extends('layouts.app')
@section('title', $donation->title)

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="bi bi-gift"></i> {{ $donation->title }}</h4>
                <span class="badge bg-{{ $donation->status === 'available' ? 'success' : ($donation->status === 'claimed' ? 'warning' : 'secondary') }} fs-6">
                    {{ ucfirst($donation->status) }}
                </span>
            </div>
            <div class="card-body">
                {{-- SECURITY (Module 1): All user content escaped via {{ }} to prevent XSS --}}
                <p>{{ $donation->description }}</p>

                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <strong><i class="bi bi-box"></i> Quantity:</strong>
                        {{ $donation->quantity }} {{ $donation->unit }}
                    </div>
                    <div class="col-md-6">
                        <strong><i class="bi bi-calendar"></i> Expires:</strong>
                        {{ $donation->expiry_date->format('d M Y, h:i A') }}
                    </div>
                    <div class="col-md-6">
                        <strong><i class="bi bi-geo-alt"></i> Pickup:</strong>
                        {{ $donation->pickup_address }}
                    </div>
                    <div class="col-md-6">
                        <strong><i class="bi bi-person"></i> Donor:</strong>
                        {{ $donation->donor->name }}
                        @if($donation->donor->organization_name)
                            ({{ $donation->donor->organization_name }})
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Food Items -->
        @if($donation->foodItems->count())
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-list-ul"></i> Food Items</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead><tr><th>Item</th><th>Qty</th><th>Category</th><th>Allergens</th><th>Expires</th></tr></thead>
                        <tbody>
                        @foreach($donation->foodItems as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->quantity }} {{ $item->unit }}</td>
                            <td>{{ $item->category?->name ?? 'N/A' }}</td>
                            <td>
                                @foreach($item->allergenTags as $tag)
                                <span class="badge bg-warning text-dark">{{ $tag->name }}</span>
                                @endforeach
                            </td>
                            <td>{{ $item->expiry_date->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <!-- Actions sidebar -->
        @if(Auth::user()->isDonor() && $donation->user_id === Auth::id())
        <div class="card mb-3">
            <div class="card-header">Actions</div>
            <div class="card-body">
                <a href="{{ route('donations.edit', $donation) }}" class="btn btn-outline-primary btn-sm w-100 mb-2">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <form method="POST" action="{{ route('donations.destroy', $donation) }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm w-100"
                            onclick="return confirm('Are you sure?')">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
        @endif

        @if(Auth::user()->isNgo() && $donation->status === 'available')
        <div class="card mb-3">
            <div class="card-header">Claim this Donation</div>
            <div class="card-body">
                <form method="POST" action="{{ route('claims.store') }}">
                    @csrf
                    <input type="hidden" name="donation_id" value="{{ $donation->id }}">
                    <div class="mb-3">
                        <label class="form-label">Justification</label>
                        <textarea name="justification" class="form-control" rows="3" required placeholder="Why does your NGO need this donation?"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pickup Date</label>
                        <input type="datetime-local" name="pickup_scheduled_at" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-ns-primary w-100">
                        <i class="bi bi-hand-thumbs-up"></i> Submit Claim
                    </button>
                </form>
            </div>
        </div>
        @endif

        <!-- Claims on this donation -->
        @if($donation->claims->count())
        <div class="card">
            <div class="card-header">Claims ({{ $donation->claims->count() }})</div>
            <div class="card-body">
                @foreach($donation->claims as $claim)
                <div class="py-2 border-bottom">
                    <strong>{{ $claim->user->organization_name ?? $claim->user->name }}</strong>
                    <br><span class="badge bg-{{ $claim->status === 'approved' ? 'success' : ($claim->status === 'pending' ? 'warning' : 'secondary') }}">
                        {{ ucfirst($claim->status) }}
                    </span>
                    <a href="{{ route('claims.show', $claim) }}" class="btn btn-sm btn-link">View</a>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
