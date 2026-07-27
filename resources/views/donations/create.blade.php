@extends('layouts.app')
@section('title', 'Publish Donation')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
@endpush

@section('content')
<div class="row justify-content-center mt-4">
    <div class="col-md-8 col-lg-7">
        <div class="card shadow-sm animate-slide-up">
            <div class="card-header text-center">
                <h4 class="mb-0"><i class="bi bi-plus-circle text-apple-accent"></i> Publish New Donation</h4>
            </div>
            <div class="card-body p-4">
                {{-- SECURITY (Module 3): @csrf prevents CSRF attacks --}}
                <form method="POST" action="{{ route('donations.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="title" class="form-label">Donation Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                               id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" step="0.01" class="form-control @error('quantity') is-invalid @enderror"
                                   id="quantity" name="quantity" value="{{ old('quantity') }}" required>
                            @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="unit" class="form-label">Unit</label>
                            <select class="form-select @error('unit') is-invalid @enderror" id="unit" name="unit" required>
                                <option value="kg" {{ old('unit') === 'kg' ? 'selected' : '' }}>Kilograms (kg)</option>
                                <option value="litres" {{ old('unit') === 'litres' ? 'selected' : '' }}>Litres</option>
                                <option value="items" {{ old('unit') === 'items' ? 'selected' : '' }}>Items</option>
                                <option value="boxes" {{ old('unit') === 'boxes' ? 'selected' : '' }}>Boxes</option>
                            </select>
                            @error('unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3 position-relative">
                        <label for="pickup_address" class="form-label">Pickup Address & Location</label>
                        <div class="input-group">
                            <input type="text" class="form-control @error('pickup_address') is-invalid @enderror"
                                   id="pickup_address" name="pickup_address" value="{{ old('pickup_address') }}" required placeholder="Type to search address..." autocomplete="off">
                            <button class="btn btn-outline-secondary" type="button" id="searchAddressBtn"><i class="bi bi-search"></i></button>
                        </div>
                        <ul id="address-suggestions" class="list-group position-absolute w-100 mt-1 shadow" style="display: none; z-index: 1000; max-height: 200px; overflow-y: auto;"></ul>
                        @error('pickup_address')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        <div id="map" style="height: 300px; width: 100%;" class="mt-2 rounded border"></div>
                        <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                        <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
                    </div>

                    <div class="mb-3">
                        <label for="expiry_date" class="form-label">Expiry Date</label>
                        <input type="datetime-local" class="form-control @error('expiry_date') is-invalid @enderror"
                               id="expiry_date" name="expiry_date" value="{{ old('expiry_date') }}" required>
                        @error('expiry_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Photo (Optional)</label>
                        <ul class="nav nav-tabs mb-3" id="photoTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="upload-tab" data-bs-toggle="tab" data-bs-target="#upload" type="button" role="tab" style="color: var(--apple-text);">Upload File</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="url-tab" data-bs-toggle="tab" data-bs-target="#url" type="button" role="tab" style="color: var(--apple-text);">Image URL</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="photoTabContent">
                            <div class="tab-pane fade show active" id="upload" role="tabpanel">
                                <div class="input-group">
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/jpeg,image/png,image/gif">
                                    <button class="btn btn-outline-secondary" type="button" onclick="document.getElementById('image').value=''" title="Clear selected file"><i class="bi bi-x-lg"></i></button>
                                </div>
                                @error('image')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="tab-pane fade" id="url" role="tabpanel">
                                <input type="url" class="form-control @error('image_url') is-invalid @enderror" id="image_url" name="image_url" placeholder="https://example.com/image.jpg">
                                @error('image_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-ns-primary w-100 py-2" style="font-weight: 500;">
                        <i class="bi bi-megaphone"></i> Publish Donation
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Default location (e.g., Kuala Lumpur)
    let defaultLat = 3.1390;
    let defaultLng = 101.6869;
    
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    const addressInput = document.getElementById('pickup_address');
    
    if (latInput.value && lngInput.value) {
        defaultLat = parseFloat(latInput.value);
        defaultLng = parseFloat(lngInput.value);
    }
    
    const map = L.map('map').setView([defaultLat, defaultLng], 12);
    
    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OpenStreetMap contributors &copy; CARTO'
    }).addTo(map);
    
    let marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);
    
    function updateInputs(lat, lng) {
        latInput.value = lat.toFixed(6);
        lngInput.value = lng.toFixed(6);
    }
    
    marker.on('dragend', function(e) {
        const position = marker.getLatLng();
        updateInputs(position.lat, position.lng);
        reverseGeocode(position.lat, position.lng);
    });
    
    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        updateInputs(e.latlng.lat, e.latlng.lng);
        reverseGeocode(e.latlng.lat, e.latlng.lng);
    });
    
    const suggestionsList = document.getElementById('address-suggestions');
    let timeoutId;

    addressInput.addEventListener('input', function() {
        clearTimeout(timeoutId);
        const query = this.value;
        if (!query) {
            suggestionsList.style.display = 'none';
            return;
        }
        
        timeoutId = setTimeout(() => {
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5`)
                .then(response => response.json())
                .then(data => {
                    suggestionsList.innerHTML = '';
                    if (data && data.length > 0) {
                        data.forEach(item => {
                            const li = document.createElement('li');
                            li.className = 'list-group-item list-group-item-action';
                            li.style.cursor = 'pointer';
                            li.style.backgroundColor = 'var(--apple-surface)';
                            li.style.color = 'var(--apple-text)';
                            li.style.border = '1px solid var(--apple-border)';
                            li.textContent = item.display_name;
                            li.addEventListener('mouseenter', () => { li.style.backgroundColor = 'rgba(255,255,255,0.1)'; });
                            li.addEventListener('mouseleave', () => { li.style.backgroundColor = 'var(--apple-surface)'; });
                            li.addEventListener('click', () => {
                                const lat = parseFloat(item.lat);
                                const lng = parseFloat(item.lon);
                                addressInput.value = item.display_name;
                                suggestionsList.style.display = 'none';
                                map.setView([lat, lng], 16);
                                marker.setLatLng([lat, lng]);
                                updateInputs(lat, lng);
                            });
                            suggestionsList.appendChild(li);
                        });
                        suggestionsList.style.display = 'block';
                    } else {
                        suggestionsList.style.display = 'none';
                    }
                });
        }, 500); // 500ms debounce
    });

    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target !== addressInput && e.target !== suggestionsList) {
            suggestionsList.style.display = 'none';
        }
    });

    document.getElementById('searchAddressBtn').addEventListener('click', function() {
        const query = addressInput.value;
        if (!query) return;
        
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    const lat = parseFloat(data[0].lat);
                    const lng = parseFloat(data[0].lon);
                    map.setView([lat, lng], 16);
                    marker.setLatLng([lat, lng]);
                    updateInputs(lat, lng);
                    addressInput.value = data[0].display_name; // update to detailed address
                } else {
                    alert('Address not found!');
                }
            });
    });
    
    function reverseGeocode(lat, lng) {
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.display_name) {
                    addressInput.value = data.display_name;
                }
            });
    }
});
</script>
@endpush
@endsection
