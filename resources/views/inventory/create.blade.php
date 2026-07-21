@extends('layouts.app')
@section('title', 'Add Inventory Location')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h4 class="mb-0"><i class="bi bi-plus-circle"></i> Register Inventory Location</h4></div>
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
                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2" required>{{ old('address') }}</textarea>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="storage_type" class="form-label">Storage Type</label>
                            <select class="form-select" id="storage_type" name="storage_type" required>
                                <option value="dry">🌡️ Dry</option>
                                <option value="cold">❄️ Cold</option>
                                <option value="frozen">🧊 Frozen</option>
                                <option value="ambient">🌤️ Ambient</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="capacity" class="form-label">Capacity (kg)</label>
                            <input type="number" step="0.01" class="form-control" id="capacity" name="capacity" value="{{ old('capacity') }}">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-ns-primary w-100"><i class="bi bi-check"></i> Register Location</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
