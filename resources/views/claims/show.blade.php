@extends('layouts.app')
@section('title', 'Claim Details')

@section('content')
<div class="row g-4">
    <div class="col-lg-7">
        <!-- Claim Details -->
        <div class="card mb-4 shadow-sm animate-slide-up">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold"><i class="bi bi-hand-thumbs-up text-apple-accent me-1"></i> Claim #{{ $claim->id }}</h4>
                <span class="badge bg-{{ $claim->status === 'approved' ? 'success' : ($claim->status === 'pending' ? 'warning' : ($claim->status === 'collected' ? 'info' : 'secondary')) }} fs-6">
                    {{ ucfirst($claim->status) }}
                </span>
            </div>
            <div class="card-body">
                @if($claim->donation->image_paths && count($claim->donation->image_paths) > 0)
                <div id="claimImageCarousel" class="carousel slide mb-3" data-bs-ride="carousel">
                    @if(count($claim->donation->image_paths) > 1)
                    <div class="carousel-indicators">
                        @foreach($claim->donation->image_paths as $index => $path)
                            <button type="button" data-bs-target="#claimImageCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}" aria-label="Slide {{ $index + 1 }}"></button>
                        @endforeach
                    </div>
                    @endif
                    <div class="carousel-inner rounded-3 shadow-sm" style="border: 1px solid var(--apple-border);">
                        @foreach($claim->donation->image_paths as $index => $path)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                @php
                                    $imgSrc = Str::startsWith($path, ['http://', 'https://']) ? $path : asset('storage/' . $path);
                                @endphp
                                <a href="javascript:void(0);" onclick="window.openClaimModalImage({{ $index }})" data-bs-toggle="modal" data-bs-target="#claimImageModal" title="Click to view full image">
                                    <img src="{{ $imgSrc }}" class="d-block w-100" alt="Claim Image {{ $index + 1 }}" style="height: 180px; object-fit: cover; cursor: pointer;">
                                </a>
                            </div>
                        @endforeach
                    </div>
                    @if(count($claim->donation->image_paths) > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#claimImageCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#claimImageCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                    @endif
                </div>
                @endif
                <h5 class="fw-bold mb-1" style="color: var(--apple-text);">Donation: {{ $claim->donation->title }}</h5>
                <p class="small mb-3" style="color: var(--apple-text-muted);">{{ $claim->donation->description }}</p>
                <div class="row g-2 small mb-3">
                    <div class="col-md-6"><strong>Quantity:</strong> {{ $claim->donation->quantity }} {{ $claim->donation->unit }}</div>
                    <div class="col-md-6"><strong>Donor:</strong> {{ $claim->donation->donor->name }}</div>
                    <div class="col-md-6"><strong>NGO:</strong> {{ $claim->user->organization_name ?? $claim->user->name }}</div>
                    <div class="col-md-6"><strong>Pickup:</strong> {{ $claim->pickup_scheduled_at?->format('d M Y, h:i A') ?? 'TBD' }}</div>
                </div>
                <hr class="my-2" style="border-color: var(--apple-border) !important;">
                <p class="small mb-3"><strong>Justification:</strong> {{ $claim->justification }}</p>

                <!-- State Pattern Info -->
                @php
                    $user = Auth::user();
                    $isReviewer = $user->isAdmin() || $user->isModerator() || $claim->donation->user_id === $user->id;
                    $isClaimingNgo = ($user->isNgo() && $claim->user_id === $user->id) || $user->isAdmin() || $user->isModerator();

                    $availableActions = array_filter($stateObject->allowedActions(), function($action) use ($user, $isReviewer, $isClaimingNgo) {
                        if (in_array($action, ['approve', 'reject'])) return $isReviewer;
                        if ($action === 'collect') return $isClaimingNgo;
                        if ($action === 'cancel') return $isClaimingNgo || $user->isAdmin() || $user->isModerator();
                        return false;
                    });

                    $canManageLogistics = $user->isAdmin() || $user->isModerator() || $claim->user_id === $user->id;
                @endphp
                <div class="alert border-0 shadow-sm" style="background: rgba(41, 151, 255, 0.12); color: var(--apple-text); border: 1px solid rgba(41, 151, 255, 0.25) !important;">
                    <strong>Current State:</strong> 
                    <span class="badge badge-{{ $claim->status === 'approved' ? 'success' : ($claim->status === 'pending' ? 'warning' : ($claim->status === 'collected' ? 'info' : 'secondary')) }} ms-1 me-2">
                        {{ ucfirst($stateObject->getStateName()) }}
                    </span>
                    @if(count($availableActions) > 0)
                        | <strong class="ms-2">Available Actions for You:</strong> <span class="text-apple-accent fw-bold">{{ implode(', ', array_map('ucfirst', $availableActions)) }}</span>
                    @elseif($claim->status === 'approved')
                        @if($canManageLogistics)
                            | <span class="ms-2" style="color: var(--apple-text-muted);"><i class="bi bi-info-circle me-1"></i> Claim Approved. Complete vehicle assignment & collection receipt generation below.</span>
                        @else
                            | <span class="ms-2" style="color: var(--apple-text-muted);"><i class="bi bi-info-circle me-1"></i> Claim Approved. Awaiting claiming NGO to complete vehicle assignment & pickup.</span>
                        @endif
                    @elseif($claim->status === 'collected')
                        @if($canManageLogistics)
                            | <span class="ms-2" style="color: var(--apple-text-muted);"><i class="bi bi-check-circle me-1"></i> Collection Completed. Record distribution logs below to measure SDG impact.</span>
                        @else
                            | <span class="ms-2" style="color: var(--apple-text-muted);"><i class="bi bi-check-circle me-1"></i> Collection Completed. Surplus food successfully collected by NGO.</span>
                        @endif
                    @elseif($claim->status === 'pending')
                        @if($isReviewer)
                            | <span class="ms-2" style="color: var(--apple-text-muted);"><i class="bi bi-info-circle me-1"></i> Claim Pending Review. Use the action buttons to Approve or Reject this claim.</span>
                        @else
                            | <span class="ms-2" style="color: var(--apple-text-muted);"><i class="bi bi-info-circle me-1"></i> Claim Pending Review. Awaiting donor or moderator approval.</span>
                        @endif
                    @else
                        | <em class="ms-2 opacity-75">No further state transitions available for this claim.</em>
                    @endif
                </div>
            </div>
        </div>

        <!-- Vehicle Info -->
        @if($claim->vehicle)
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-truck text-apple-accent"></i> Pickup & Transport Logistics</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4"><strong>Plate:</strong> {{ $claim->vehicle->plate_number }}</div>
                    <div class="col-md-4"><strong>Type:</strong> {{ ucfirst($claim->vehicle->vehicle_type) }}</div>
                    <div class="col-md-4"><strong>Driver:</strong> {{ $claim->vehicle->driver_name }}</div>
                </div>
            </div>
        </div>
        @endif

        <!-- Collection Receipt -->
        @if($claim->collectionReceipt)
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-receipt"></i> Collection Receipt</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4"><strong>Receipt #:</strong> {{ $claim->collectionReceipt->receipt_number }}</div>
                    <div class="col-md-4"><strong>Collected:</strong> {{ $claim->collectionReceipt->quantity_collected }} {{ $claim->collectionReceipt->unit }}</div>
                    <div class="col-md-4"><strong>By:</strong> {{ $claim->collectionReceipt->collected_by }}</div>
                </div>
                @if($claim->collectionReceipt->condition_notes)
                <p class="mt-2"><strong>Condition:</strong> {{ $claim->collectionReceipt->condition_notes }}</p>
                @endif
            </div>
        </div>
        @endif

        <!-- Distribution Logs -->
        @if($claim->distributionLogs->count())
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-people text-apple-accent"></i> Distribution Logs (SDG Impact)</span>
                <span class="badge border px-3 py-1" style="background-color: var(--apple-input-bg); color: var(--apple-text); border-color: var(--apple-border) !important; font-size: 0.75rem; font-weight: 500;">
                    {{ $claim->distributionLogs->count() }} Entries
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr class="border-bottom" style="border-color: var(--apple-border) !important;">
                                <th class="ps-4 py-3 fw-semibold small" style="color: var(--apple-text-muted);">Date</th>
                                <th class="py-3 fw-semibold small" style="color: var(--apple-text-muted);">Location</th>
                                <th class="py-3 fw-semibold small" style="color: var(--apple-text-muted);">Beneficiaries</th>
                                <th class="pe-4 py-3 fw-semibold small text-end" style="color: var(--apple-text-muted);">Qty Distributed</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($claim->distributionLogs as $log)
                        <tr class="border-bottom" style="border-color: var(--apple-border) !important;">
                            <td class="ps-4 small" style="color: var(--apple-text-muted);"><i class="bi bi-calendar me-1"></i>{{ $log->distributed_at->format('d M Y') }}</td>
                            <td style="color: var(--apple-text);"><i class="bi bi-geo-alt text-apple-accent me-1"></i>{{ $log->distribution_location }}</td>
                            <td><span class="badge badge-success fw-bold">{{ $log->beneficiaries_count }} People</span></td>
                            <td class="pe-4 text-end" style="color: var(--apple-text);">{{ $log->quantity_distributed }} {{ $log->unit }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-5" style="position: sticky; top: 20px; align-self: flex-start;">
        <!-- State Transition Actions -->
        @if(count($availableActions) > 0)
        <div class="card mb-3">
            <div class="card-header">Actions</div>
            <div class="card-body">
                @foreach($availableActions as $action)
                <form method="POST" action="{{ route('claims.transition', $claim) }}" class="mb-2">
                    @csrf
                    <input type="hidden" name="action" value="{{ $action }}">
                    @if($action === 'collect' && !$claim->vehicle)
                        <button type="button" class="btn btn-secondary btn-sm w-100" disabled title="Vehicle assignment required before collection">
                            <i class="bi bi-lock me-1"></i> Collect (Assign Vehicle Below First)
                        </button>
                        <small class="text-warning d-block mt-1 text-center" style="font-size: 0.72rem;">
                            <i class="bi bi-exclamation-triangle me-1"></i>Assign driver & vehicle below to enable collection.
                        </small>
                    @else
                        <button type="submit" class="btn btn-{{ $action === 'approve' ? 'success' : ($action === 'reject' ? 'danger' : ($action === 'collect' ? 'primary' : 'secondary')) }} btn-sm w-100"
                                onclick="return confirm('Are you sure you want to {{ $action }} this claim?')">
                            <i class="bi bi-{{ $action === 'approve' ? 'check-circle' : ($action === 'reject' ? 'x-circle' : ($action === 'collect' ? 'box-arrow-down' : 'arrow-left')) }}"></i>
                            {{ ucfirst($action) }}
                        </button>
                    @endif
                </form>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Assign Vehicle Form -->
        @if((Auth::user()->isAdmin() || $claim->user_id === Auth::id()) && $claim->status === 'approved' && !$claim->vehicle)
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-truck me-1"></i> Dispatch Transport & Driver</div>
            <div class="card-body">
                <form method="POST" action="{{ route('claims.vehicle', $claim) }}">
                    @csrf
                    <div class="mb-2">
                        <input type="text" name="plate_number" class="form-control form-control-sm @error('plate_number') is-invalid @enderror" placeholder="Plate Number" value="{{ old('plate_number') }}" required>
                        @error('plate_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-2">
                        <select name="vehicle_type" class="form-select form-select-sm @error('vehicle_type') is-invalid @enderror" required>
                            <option value="van" {{ old('vehicle_type') == 'van' ? 'selected' : '' }}>Van</option>
                            <option value="truck" {{ old('vehicle_type') == 'truck' ? 'selected' : '' }}>Truck</option>
                            <option value="car" {{ old('vehicle_type') == 'car' ? 'selected' : '' }}>Car</option>
                            <option value="motorcycle" {{ old('vehicle_type') == 'motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                        </select>
                        @error('vehicle_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-2">
                        <input type="text" name="driver_name" class="form-control form-control-sm @error('driver_name') is-invalid @enderror" placeholder="Driver Name" value="{{ old('driver_name') }}" required>
                        @error('driver_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-2">
                        <input type="text" name="driver_phone" class="form-control form-control-sm @error('driver_phone') is-invalid @enderror" placeholder="Driver Phone" value="{{ old('driver_phone') }}" required>
                        @error('driver_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-outline-primary btn-sm w-100">Assign Vehicle</button>
                </form>
            </div>
        </div>
        @endif



        <!-- Distribution Log Form -->
        @if((Auth::user()->isAdmin() || Auth::user()->isModerator() || $claim->user_id === Auth::id()) && $claim->status === 'collected')
        <div class="card mb-3 shadow-sm animate-slide-up">
            <div class="card-header"><i class="bi bi-globe-americas text-apple-success me-1"></i> Log Distribution (SDG)</div>
            <div class="card-body">
                <form method="POST" action="{{ route('claims.distribution', $claim) }}">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label text-muted small mb-1"><i class="bi bi-people me-1"></i> Beneficiaries Count</label>
                        <input type="number" name="beneficiaries_count" class="form-control form-control-sm @error('beneficiaries_count') is-invalid @enderror" placeholder="Beneficiaries Count" value="{{ old('beneficiaries_count', max(10, (int) round($claim->donation->quantity * 5))) }}" required min="1">
                        @error('beneficiaries_count')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-2">
                        <label class="form-label text-muted small mb-1"><i class="bi bi-geo-alt me-1"></i> Distribution Location</label>
                        <input type="text" name="distribution_location" class="form-control form-control-sm @error('distribution_location') is-invalid @enderror" placeholder="Distribution Location" value="{{ old('distribution_location', Auth::user()->inventoryLocations->first()?->name ? (Auth::user()->inventoryLocations->first()->name . ' (' . (Auth::user()->inventoryLocations->first()->address ?: 'NGO Facility') . ')') : ((Auth::user()->organization_name ?? Auth::user()->name) . ' Distribution Center')) }}" required>
                        @error('distribution_location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-2">
                        <label class="form-label text-muted small mb-1"><i class="bi bi-box-seam me-1"></i> Quantity</label>
                        <input type="number" step="0.01" name="quantity_distributed" class="form-control form-control-sm @error('quantity_distributed') is-invalid @enderror" placeholder="Quantity" value="{{ old('quantity_distributed', $claim->donation->quantity) }}" required>
                        @error('quantity_distributed')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-2">
                        <label class="form-label text-muted small mb-1"><i class="bi bi-tag me-1"></i> Unit</label>
                        <select name="unit" class="form-select form-select-sm @error('unit') is-invalid @enderror" required>
                            <option value="kg" {{ old('unit', $claim->donation->unit) == 'kg' ? 'selected' : '' }}>kg</option>
                            <option value="litres" {{ old('unit', $claim->donation->unit) == 'litres' ? 'selected' : '' }}>litres</option>
                            <option value="items" {{ old('unit', $claim->donation->unit) == 'items' ? 'selected' : '' }}>items</option>
                            <option value="boxes" {{ old('unit', $claim->donation->unit) == 'boxes' ? 'selected' : '' }}>boxes</option>
                        </select>
                        @error('unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-2">
                        <label class="form-label text-muted small mb-1"><i class="bi bi-journal-text me-1"></i> Notes</label>
                        <textarea name="notes" class="form-control form-control-sm @error('notes') is-invalid @enderror" rows="2" placeholder="Distribution Notes (Optional)">{{ old('notes') }}</textarea>
                        @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-ns-primary btn-sm w-100 mt-2 fw-medium">
                        <i class="bi bi-plus-lg me-1"></i> Submit Distribution Log
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>

@if($claim->donation->image_paths && count($claim->donation->image_paths) > 0)
<!-- Modal for Fullscreen Image Viewing -->
<div class="modal fade" id="claimImageModal" tabindex="-1" aria-labelledby="claimImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark text-light border-secondary">
            <div class="modal-header border-secondary py-2">
                <h6 class="modal-title" id="claimImageModalLabel"><i class="bi bi-image text-apple-accent"></i> {{ $claim->donation->title }} — Photo Gallery</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-0 position-relative d-flex justify-content-center align-items-center" style="min-height: 400px; background: #000;">
                <img id="claimModalImage" src="" class="img-fluid" style="max-height: 75vh; object-fit: contain;" alt="Full Donation Image">
                <button type="button" class="btn btn-dark btn-sm position-absolute start-0 top-50 translate-middle-y ms-2 rounded-circle border-secondary" id="claimModalPrevBtn" style="width: 40px; height: 40px; opacity: 0.8;">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <button type="button" class="btn btn-dark btn-sm position-absolute end-0 top-50 translate-middle-y me-2 rounded-circle border-secondary" id="claimModalNextBtn" style="width: 40px; height: 40px; opacity: 0.8;">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const claimModalImages = [
        @foreach($claim->donation->image_paths as $path)
            '{{ Str::startsWith($path, ["http://", "https://"]) ? $path : asset("storage/" . $path) }}',
        @endforeach
    ];
    let currentClaimModalIndex = 0;

    window.openClaimModalImage = function(index) {
        currentClaimModalIndex = index;
        updateClaimModalImage();
    };

    function updateClaimModalImage() {
        if(claimModalImages.length > 0) {
            document.getElementById('claimModalImage').src = claimModalImages[currentClaimModalIndex];
            const showButtons = claimModalImages.length > 1;
            document.getElementById('claimModalPrevBtn').style.display = showButtons ? 'block' : 'none';
            document.getElementById('claimModalNextBtn').style.display = showButtons ? 'block' : 'none';
        }
    }

    if (document.getElementById('claimModalPrevBtn')) {
        document.getElementById('claimModalPrevBtn').addEventListener('click', function(e) {
            e.preventDefault();
            currentClaimModalIndex = (currentClaimModalIndex - 1 + claimModalImages.length) % claimModalImages.length;
            updateClaimModalImage();
        });
    }

    if (document.getElementById('claimModalNextBtn')) {
        document.getElementById('claimModalNextBtn').addEventListener('click', function(e) {
            e.preventDefault();
            currentClaimModalIndex = (currentClaimModalIndex + 1) % claimModalImages.length;
            updateClaimModalImage();
        });
    }
</script>
@endpush
@endif
@endsection
