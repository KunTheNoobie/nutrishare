@extends('layouts.app')
@section('title', 'Edit Donation')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-pencil"></i> Edit Donation</h4>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('donations.update', $donation) }}" enctype="multipart/form-data">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                               id="title" name="title" value="{{ old('title', $donation->title) }}" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="3" required>{{ old('description', $donation->description) }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" step="0.01" class="form-control" id="quantity" name="quantity"
                                   value="{{ old('quantity', $donation->quantity) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="unit" class="form-label">Unit</label>
                            <select class="form-select" id="unit" name="unit" required>
                                @foreach(['kg', 'litres', 'items', 'boxes'] as $u)
                                <option value="{{ $u }}" {{ old('unit', $donation->unit) === $u ? 'selected' : '' }}>{{ ucfirst($u) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="pickup_address" class="form-label">Pickup Address</label>
                        <input type="text" class="form-control" id="pickup_address" name="pickup_address"
                               value="{{ old('pickup_address', $donation->pickup_address) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="expiry_date" class="form-label">Expiry Date</label>
                        <input type="datetime-local" class="form-control" id="expiry_date" name="expiry_date"
                               value="{{ old('expiry_date', $donation->expiry_date->format('Y-m-d\TH:i')) }}" required>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-ns-primary"><i class="bi bi-check"></i> Update</button>
                        <a href="{{ route('donations.show', $donation) }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
