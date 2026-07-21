@extends('layouts.app')
@section('title', 'Publish Donation')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-plus-circle"></i> Publish New Donation</h4>
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

                    <div class="mb-3">
                        <label for="pickup_address" class="form-label">Pickup Address</label>
                        <input type="text" class="form-control @error('pickup_address') is-invalid @enderror"
                               id="pickup_address" name="pickup_address" value="{{ old('pickup_address') }}" required>
                        @error('pickup_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="expiry_date" class="form-label">Expiry Date</label>
                        <input type="datetime-local" class="form-control @error('expiry_date') is-invalid @enderror"
                               id="expiry_date" name="expiry_date" value="{{ old('expiry_date') }}" required>
                        @error('expiry_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Photo (Optional)</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/jpeg,image/png">
                    </div>

                    <button type="submit" class="btn btn-ns-primary w-100">
                        <i class="bi bi-megaphone"></i> Publish Donation
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
