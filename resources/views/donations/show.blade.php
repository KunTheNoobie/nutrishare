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
                <h4 class="mb-0"><i class="bi bi-basket"></i> {{ $donation->title }}</h4>
                <span class="badge bg-{{ $donation->status === 'available' ? 'success' : ($donation->status === 'claimed' ? 'warning' : 'secondary') }} fs-6">
                    {{ ucfirst($donation->status) }}
                </span>
            </div>
            <div class="card-body">
                {{-- SECURITY (Module 1): All user content escaped via {{ }} to prevent XSS --}}
                @if($donation->image_paths && count($donation->image_paths) > 0)
                <div id="donationImageCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        @foreach($donation->image_paths as $index => $path)
                            <button type="button" data-bs-target="#donationImageCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}" aria-label="Slide {{ $index + 1 }}"></button>
                        @endforeach
                    </div>
                    <div class="carousel-inner rounded shadow-sm">
                        @foreach($donation->image_paths as $index => $path)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                @php
                                    $imgSrc = Str::startsWith($path, ['http://', 'https://']) ? $path : asset('storage/' . $path);
                                @endphp
                                <a href="javascript:void(0);" onclick="window.openModalImage({{ $index }})" data-bs-toggle="modal" data-bs-target="#imageModal" title="Click to view full image">
                                    <img src="{{ $imgSrc }}" class="d-block w-100" alt="Donation Image {{ $index + 1 }}" style="height: 400px; object-fit: cover;">
                                </a>
                            </div>
                        @endforeach
                    </div>
                    @if(count($donation->image_paths) > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#donationImageCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#donationImageCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
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
                        <div class="d-flex align-items-center mb-2 mt-1">
                            <span id="pickup-address-text" class="me-2">{{ $donation->pickup_address }}</span>
                            <button class="btn btn-sm btn-outline-secondary" onclick="navigator.clipboard.writeText(document.getElementById('pickup-address-text').innerText); this.innerHTML='<i class=\'bi bi-check2\'></i> Copied!'; setTimeout(() => this.innerHTML='<i class=\'bi bi-clipboard\'></i> Copy', 2000);" style="padding: 2px 8px; font-size: 0.75rem;">
                                <i class="bi bi-clipboard"></i> Copy
                            </button>
                        </div>
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
        @if((Auth::user()->isNgo() || Auth::user()->isAdmin() || Auth::user()->isModerator()) && $donation->foodItems->count())
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
                            <textarea name="justification" class="form-control @error('justification') is-invalid @enderror" rows="3" required placeholder="Why does your NGO need this donation?">{{ old('justification') }}</textarea>
                            @error('justification')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">Pickup Date</label>
                            <input type="datetime-local" name="pickup_scheduled_at" class="form-control @error('pickup_scheduled_at') is-invalid @enderror" value="{{ old('pickup_scheduled_at') }}" required>
                            @error('pickup_scheduled_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                <div class="py-3 border-bottom d-flex justify-content-between align-items-center" style="border-color: var(--apple-border) !important;">
                    <div>
                        <strong class="d-block mb-1 text-light">{{ $claim->user->organization_name ?? $claim->user->name }}</strong>
                        <span class="badge badge-{{ $claim->status === 'approved' ? 'success' : ($claim->status === 'pending' ? 'warning' : 'secondary') }}">
                            {{ ucfirst($claim->status) }}
                        </span>
                    </div>
                    <a href="{{ route('claims.show', $claim) }}" class="btn btn-sm btn-outline-light">View Details</a>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-transparent border-0 shadow-none">
            <div class="modal-header border-0 pb-0 justify-content-end">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pt-0 position-relative">
                <button class="btn btn-dark position-absolute top-50 start-0 translate-middle-y ms-2 ms-md-4" id="modalPrevBtn" style="border-radius: 50%; width: 45px; height: 45px; z-index: 1055; opacity: 0.8; display: none;">
                    <i class="bi bi-chevron-left fs-5"></i>
                </button>
                
                <img id="modalImage" src="" class="img-fluid rounded shadow" alt="Full Image" style="max-height: 85vh;">
                
                <button class="btn btn-dark position-absolute top-50 end-0 translate-middle-y me-2 me-md-4" id="modalNextBtn" style="border-radius: 50%; width: 45px; height: 45px; z-index: 1055; opacity: 0.8; display: none;">
                    <i class="bi bi-chevron-right fs-5"></i>
                </button>
            </div>
        </div>
    </div>
</div>

@if($donation->image_paths && count($donation->image_paths) > 0)
@push('scripts')
<script>
    const modalImages = [
        @foreach($donation->image_paths as $path)
            '{{ Str::startsWith($path, ["http://", "https://"]) ? $path : asset("storage/" . $path) }}',
        @endforeach
    ];
    let currentModalIndex = 0;

    window.openModalImage = function(index) {
        currentModalIndex = index;
        updateModalImage();
    };

    function updateModalImage() {
        if(modalImages.length > 0) {
            document.getElementById('modalImage').src = modalImages[currentModalIndex];
            
            const showButtons = modalImages.length > 1;
            document.getElementById('modalPrevBtn').style.display = showButtons ? 'block' : 'none';
            document.getElementById('modalNextBtn').style.display = showButtons ? 'block' : 'none';
        }
    }

    if (document.getElementById('modalPrevBtn')) {
        document.getElementById('modalPrevBtn').addEventListener('click', function(e) {
            e.preventDefault();
            currentModalIndex = (currentModalIndex - 1 + modalImages.length) % modalImages.length;
            updateModalImage();
        });
    }

    if (document.getElementById('modalNextBtn')) {
        document.getElementById('modalNextBtn').addEventListener('click', function(e) {
            e.preventDefault();
            currentModalIndex = (currentModalIndex + 1) % modalImages.length;
            updateModalImage();
        });
    }
</script>
@endpush
@endif

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
