@extends('layouts.app')
@section('title', $donation->title)

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
@endpush

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
                @if($donation->image_path)
                <div class="mb-4 text-center">
                    @if(Str::startsWith($donation->image_path, ['http://', 'https://']))
                        <img src="{{ $donation->image_path }}" class="img-fluid rounded shadow-sm" alt="Donation Image" style="max-height: 400px; object-fit: cover;">
                    @else
                        <img src="{{ asset('storage/' . $donation->image_path) }}" class="img-fluid rounded shadow-sm" alt="Donation Image" style="max-height: 400px; object-fit: cover;">
                    @endif
                </div>
                @endif
                <p style="font-size: 1.05rem; line-height: 1.6;">{{ $donation->description }}</p>

                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <strong><i class="bi bi-box"></i> Quantity:</strong>
                        {{ $donation->quantity }} {{ $donation->unit }}
                    </div>
                    <div class="col-md-6">
                        <strong><i class="bi bi-calendar"></i> Expires:</strong>
                        {{ $donation->expiry_date->format('d M Y, h:i A') }}
                    </div>
                    <div class="col-md-12">
                        <strong><i class="bi bi-geo-alt"></i> Pickup Location:</strong>
                        {{ $donation->pickup_address }}
                        @if($donation->latitude && $donation->longitude)
                            <div id="map" style="height: 250px; width: 100%;" class="mt-2 rounded border"></div>
                            <input type="hidden" id="donation_lat" value="{{ $donation->latitude }}">
                            <input type="hidden" id="donation_lng" value="{{ $donation->longitude }}">
                        @endif
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
            @if(Auth::user()->isVerified())
            <div class="card mb-3 shadow-sm animate-slide-up">
                <div class="card-header"><i class="bi bi-hand-thumbs-up text-apple-success"></i> Claim this Donation</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('claims.store') }}">
                        @csrf
                        <input type="hidden" name="donation_id" value="{{ $donation->id }}">
                        <div class="mb-3">
                            <label class="form-label text-muted small">Justification</label>
                            <textarea name="justification" class="form-control" rows="3" required placeholder="Why does your NGO need this donation?"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">Pickup Date</label>
                            <input type="datetime-local" name="pickup_scheduled_at" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-ns-primary w-100 py-2 fw-medium">
                            Submit Claim
                        </button>
                    </form>
                </div>
            </div>
            @else
            <div class="alert alert-warning shadow-sm animate-slide-up">
                <i class="bi bi-exclamation-triangle"></i> <strong>Account Pending Verification.</strong> You cannot claim donations until an administrator verifies your NGO account.
            </div>
            @endif
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

@if($donation->latitude && $donation->longitude)
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const lat = parseFloat(document.getElementById('donation_lat').value);
    const lng = parseFloat(document.getElementById('donation_lng').value);
    
    if (document.getElementById('map')) {
        const map = L.map('map').setView([lat, lng], 15);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap contributors &copy; CARTO'
        }).addTo(map);
        
        L.marker([lat, lng]).addTo(map)
            .bindPopup("<b>{{ addslashes($donation->title) }}</b><br>Pickup Location")
            .openPopup();
    }
});
</script>
@endpush
@endif
@endsection
