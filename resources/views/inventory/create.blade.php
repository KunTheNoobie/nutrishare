@extends('layouts.app')
@section('title', 'Add Inventory Location')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    #map { height: 300px; width: 100%; border-radius: 8px; z-index: 1; }
</style>
@endpush

@section('content')
<div class="row justify-content-center mt-4">
    <div class="col-md-8 col-lg-7">
        <div class="card shadow-sm animate-slide-up">
            <div class="card-header text-center">
                <h4 class="mb-0"><i class="bi bi-plus-circle text-apple-accent"></i> Register Inventory Location</h4>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('inventory.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Location Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <div class="input-group">
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2" required placeholder="Type an address or click on the map...">{{ old('address') }}</textarea>
                            <button class="btn btn-outline-secondary" type="button" id="searchAddressBtn"><i class="bi bi-search"></i> Search Map</button>
                        </div>
                        @error('address')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-3">
                        <div id="map" class="border border-dark"></div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="storage_type" class="form-label">Storage Type</label>
                            <select class="form-select @error('storage_type') is-invalid @enderror" id="storage_type" name="storage_type" required>
                                <option value="dry" {{ old('storage_type') == 'dry' ? 'selected' : '' }}>🌡️ Dry</option>
                                <option value="cold" {{ old('storage_type') == 'cold' ? 'selected' : '' }}>❄️ Cold</option>
                                <option value="frozen" {{ old('storage_type') == 'frozen' ? 'selected' : '' }}>🧊 Frozen</option>
                                <option value="ambient" {{ old('storage_type') == 'ambient' ? 'selected' : '' }}>🌤️ Ambient</option>
                            </select>
                            @error('storage_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="capacity" class="form-label">Capacity (kg)</label>
                            <input type="number" step="0.01" class="form-control @error('capacity') is-invalid @enderror" id="capacity" name="capacity" value="{{ old('capacity') }}">
                            @error('capacity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-ns-primary w-100 py-2"><i class="bi bi-check"></i> Register Location</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let defaultLat = 40.7128; // Default to Metro City (NYC)
    let defaultLng = -74.0060;
    
    const addressInput = document.getElementById('address');
    
    const map = L.map('map').setView([defaultLat, defaultLng], 12);
    
    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OpenStreetMap contributors &copy; CARTO'
    }).addTo(map);
    
    let marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);
    
    marker.on('dragend', function(e) {
        const position = marker.getLatLng();
        reverseGeocode(position.lat, position.lng);
    });
    
    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        reverseGeocode(e.latlng.lat, e.latlng.lng);
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
                    addressInput.value = data[0].display_name;
                } else {
                    alert('Address not found on the map!');
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
