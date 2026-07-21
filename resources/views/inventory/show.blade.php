@extends('layouts.app')
@section('title', $inventoryLocation->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>{{ $inventoryLocation->name }}</h2>
        <p class="text-muted mb-0">{{ $inventoryLocation->address }}</p>
    </div>
    <span class="badge bg-{{ $inventoryLocation->storage_type === 'cold' ? 'info' : 'success' }} fs-6">
        {{ ucfirst($inventoryLocation->storage_type) }} Storage
    </span>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Food Items -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between">
                <span><i class="bi bi-list-ul"></i> Food Items</span>
                <span class="badge bg-secondary">{{ $inventoryLocation->foodItems->count() }} items</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead><tr><th>Item</th><th>Qty</th><th>Category</th><th>Allergens</th><th>Expires</th><th>Storage</th></tr></thead>
                        <tbody>
                        @forelse($inventoryLocation->foodItems as $item)
                        <tr class="{{ $item->isExpired() ? 'table-danger' : '' }}">
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->quantity }} {{ $item->unit }}</td>
                            <td>{{ $item->category?->name ?? '—' }}</td>
                            <td>
                                @foreach($item->allergenTags as $tag)
                                <span class="badge bg-warning text-dark">{{ $tag->name }}</span>
                                @endforeach
                            </td>
                            <td>{{ $item->expiry_date->format('d M Y') }}</td>
                            <td>{{ ucfirst($item->storage_requirements) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-muted text-center">No food items yet.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Add Food Item Form -->
        <div class="card">
            <div class="card-header">Add Food Item</div>
            <div class="card-body">
                <form method="POST" action="{{ route('inventory.add-food-item') }}">
                    @csrf
                    <input type="hidden" name="inventory_location_id" value="{{ $inventoryLocation->id }}">
                    <input type="hidden" name="donation_id" value="{{ $inventoryLocation->foodItems->first()?->donation_id ?? 1 }}">

                    <div class="mb-2">
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="Food Item Name" required>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-7"><input type="number" step="0.01" name="quantity" class="form-control form-control-sm" placeholder="Qty" required></div>
                        <div class="col-5">
                            <select name="unit" class="form-select form-select-sm">
                                <option value="kg">kg</option><option value="litres">litres</option><option value="items">items</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-2">
                        <select name="category_id" class="form-select form-select-sm">
                            <option value="">No Category</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <input type="datetime-local" name="expiry_date" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-2">
                        <select name="storage_requirements" class="form-select form-select-sm">
                            <option value="dry">Dry</option><option value="cold">Cold</option>
                            <option value="frozen">Frozen</option><option value="ambient">Ambient</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <select name="is_perishable" class="form-select form-select-sm">
                            <option value="1">Perishable</option><option value="0">Non-Perishable</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">Allergen Tags</label>
                        @foreach($allergenTags as $tag)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="allergen_tags[]" value="{{ $tag->id }}" id="allergen{{ $tag->id }}">
                            <label class="form-check-label small" for="allergen{{ $tag->id }}">{{ $tag->name }}</label>
                        </div>
                        @endforeach
                    </div>
                    <button type="submit" class="btn btn-ns-primary btn-sm w-100">Add Item</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
