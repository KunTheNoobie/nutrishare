@extends('layouts.app')
@section('title', $inventoryLocation->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 animate-slide-up">
    <div>
        <h2 style="font-weight: 600;">{{ $inventoryLocation->name }}</h2>
        <p class="text-muted mb-0"><i class="bi bi-geo-alt text-apple-accent"></i> {{ $inventoryLocation->address }}</p>
    </div>
    <span class="badge bg-{{ $inventoryLocation->storage_type === 'cold' ? 'info' : 'success' }} fs-6 py-2 px-3 shadow-sm rounded-pill">
        {{ ucfirst($inventoryLocation->storage_type) }} Storage
    </span>
</div>

<div class="row animate-slide-up" style="animation-delay: 0.1s;">
    <div class="col-md-8">
        <!-- Food Items -->
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-list-ul text-apple-accent"></i> Food Items</span>
                <span class="badge bg-secondary rounded-pill">{{ $inventoryLocation->foodItems->count() }} items</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-dark table-hover border-dark datatable">
                        <thead>
                            <tr>
                                <th class="text-muted fw-normal">Item</th>
                                <th class="text-muted fw-normal">Qty</th>
                                <th class="text-muted fw-normal">Category</th>
                                <th class="text-muted fw-normal">Allergens</th>
                                <th class="text-muted fw-normal">Expires</th>
                                <th class="text-muted fw-normal">Storage</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($inventoryLocation->foodItems as $item)
                        <tr class="{{ $item->isExpired() ? 'table-danger' : '' }}">
                            <td><strong>{{ $item->name }}</strong></td>
                            <td>{{ $item->quantity }} {{ $item->unit }}</td>
                            <td>
                                @if($item->category)
                                    <span class="badge bg-secondary">{{ $item->category->name }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @forelse($item->allergenTags as $tag)
                                <span class="badge bg-warning text-dark">{{ $tag->name }}</span>
                                @empty
                                <span class="text-muted">—</span>
                                @endforelse
                            </td>
                            <td>{{ $item->expiry_date->format('d M Y') }}</td>
                            <td>{{ ucfirst($item->storage_requirements) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-muted text-center py-4">No food items yet. Use the form to add some!</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Add Food Item Form -->
        <div class="card shadow-sm">
            <div class="card-header"><i class="bi bi-plus-circle text-apple-success"></i> Add Food Item</div>
            <div class="card-body">
                <form method="POST" action="{{ route('inventory.add-food-item') }}">
                    @csrf
                    <input type="hidden" name="inventory_location_id" value="{{ $inventoryLocation->id }}">
                    
                    @if(Auth::user()->donations->count() > 0)
                    <div class="mb-3">
                        <label class="form-label text-muted small">Link to Donation (Optional)</label>
                        <select name="donation_id" class="form-select @error('donation_id') is-invalid @enderror">
                            <option value="">-- None --</option>
                            @foreach(Auth::user()->donations as $donation)
                                <option value="{{ $donation->id }}">{{ $donation->title }}</option>
                            @endforeach
                        </select>
                        @error('donation_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    @endif

                    <div class="mb-3">
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Food Item Name" value="{{ old('name') }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="row g-2 mb-3">
                        <div class="col-7">
                            <input type="number" step="0.01" name="quantity" class="form-control @error('quantity') is-invalid @enderror" placeholder="Qty (e.g. 10.5)" value="{{ old('quantity') }}" required>
                            @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-5">
                            <select name="unit" class="form-select @error('unit') is-invalid @enderror">
                                <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>kg</option>
                                <option value="litres" {{ old('unit') == 'litres' ? 'selected' : '' }}>litres</option>
                                <option value="items" {{ old('unit') == 'items' ? 'selected' : '' }}>items</option>
                            </select>
                            @error('unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small">Expiry Date</label>
                        <input type="datetime-local" name="expiry_date" class="form-control @error('expiry_date') is-invalid @enderror" value="{{ old('expiry_date') }}" required>
                        @error('expiry_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-3">
                        <select name="storage_requirements" class="form-select @error('storage_requirements') is-invalid @enderror">
                            <option value="dry" {{ old('storage_requirements') == 'dry' ? 'selected' : '' }}>Dry Storage</option>
                            <option value="cold" {{ old('storage_requirements') == 'cold' ? 'selected' : '' }}>Cold Storage</option>
                            <option value="frozen" {{ old('storage_requirements') == 'frozen' ? 'selected' : '' }}>Frozen Storage</option>
                            <option value="ambient" {{ old('storage_requirements') == 'ambient' ? 'selected' : '' }}>Ambient Storage</option>
                        </select>
                        @error('storage_requirements')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-3">
                        <select name="is_perishable" class="form-select @error('is_perishable') is-invalid @enderror">
                            <option value="1" {{ old('is_perishable') == '1' ? 'selected' : '' }}>Highly Perishable</option>
                            <option value="0" {{ old('is_perishable') == '0' ? 'selected' : '' }}>Non-Perishable</option>
                        </select>
                        @error('is_perishable')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label text-muted small mb-2 d-block">Allergen Tags</label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($allergenTags as $tag)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="allergen_tags[]" value="{{ $tag->id }}" id="allergen{{ $tag->id }}" {{ is_array(old('allergen_tags')) && in_array($tag->id, old('allergen_tags')) ? 'checked' : '' }}>
                                <label class="form-check-label text-muted small" for="allergen{{ $tag->id }}">{{ $tag->name }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-ns-primary w-100 py-2 fw-medium">
                        <i class="bi bi-plus-lg"></i> Add Food Item
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
