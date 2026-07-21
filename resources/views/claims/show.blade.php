@extends('layouts.app')
@section('title', 'Claim Details')

@section('content')
<div class="row">
    <div class="col-md-8">
        <!-- Claim Details -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between">
                <h4 class="mb-0"><i class="bi bi-hand-thumbs-up"></i> Claim #{{ $claim->id }}</h4>
                <span class="badge bg-{{ $claim->status === 'approved' ? 'success' : ($claim->status === 'pending' ? 'warning' : ($claim->status === 'collected' ? 'info' : 'secondary')) }} fs-6">
                    {{ ucfirst($claim->status) }}
                </span>
            </div>
            <div class="card-body">
                <h5>Donation: {{ $claim->donation->title }}</h5>
                <p>{{ $claim->donation->description }}</p>
                <div class="row g-3">
                    <div class="col-md-6"><strong>Quantity:</strong> {{ $claim->donation->quantity }} {{ $claim->donation->unit }}</div>
                    <div class="col-md-6"><strong>Donor:</strong> {{ $claim->donation->donor->name }}</div>
                    <div class="col-md-6"><strong>NGO:</strong> {{ $claim->user->organization_name ?? $claim->user->name }}</div>
                    <div class="col-md-6"><strong>Pickup:</strong> {{ $claim->pickup_scheduled_at?->format('d M Y, h:i A') ?? 'TBD' }}</div>
                </div>
                <hr>
                <p><strong>Justification:</strong> {{ $claim->justification }}</p>

                <!-- State Pattern Info -->
                <div class="alert alert-info">
                    <strong>Current State:</strong> {{ ucfirst($stateObject->getStateName()) }}
                    @if(count($stateObject->allowedActions()) > 0)
                        | <strong>Available Actions:</strong> {{ implode(', ', $stateObject->allowedActions()) }}
                    @else
                        | <em>No further transitions (terminal state)</em>
                    @endif
                </div>
            </div>
        </div>

        <!-- Vehicle Info -->
        @if($claim->vehicle)
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-truck"></i> Vehicle Assignment</div>
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
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-people"></i> Distribution Logs (SDG Impact)</div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead><tr><th>Date</th><th>Location</th><th>Beneficiaries</th><th>Qty</th></tr></thead>
                    <tbody>
                    @foreach($claim->distributionLogs as $log)
                    <tr>
                        <td>{{ $log->distributed_at->format('d M Y') }}</td>
                        <td>{{ $log->distribution_location }}</td>
                        <td>{{ $log->beneficiaries_count }}</td>
                        <td>{{ $log->quantity_distributed }} {{ $log->unit }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <!-- State Transition Actions -->
        @if(count($stateObject->allowedActions()) > 0)
        <div class="card mb-3">
            <div class="card-header">Actions</div>
            <div class="card-body">
                @foreach($stateObject->allowedActions() as $action)
                <form method="POST" action="{{ route('claims.transition', $claim) }}" class="mb-2">
                    @csrf
                    <input type="hidden" name="action" value="{{ $action }}">
                    <button type="submit" class="btn btn-{{ $action === 'approve' ? 'success' : ($action === 'reject' ? 'danger' : ($action === 'collect' ? 'primary' : 'secondary')) }} btn-sm w-100"
                            onclick="return confirm('Are you sure you want to {{ $action }} this claim?')">
                        <i class="bi bi-{{ $action === 'approve' ? 'check-circle' : ($action === 'reject' ? 'x-circle' : ($action === 'collect' ? 'box-arrow-down' : 'arrow-left')) }}"></i>
                        {{ ucfirst($action) }}
                    </button>
                </form>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Assign Vehicle Form -->
        @if($claim->status === 'approved' && !$claim->vehicle)
        <div class="card mb-3">
            <div class="card-header">Assign Vehicle</div>
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

        <!-- Generate Receipt Form -->
        @if($claim->status === 'approved' && !$claim->collectionReceipt)
        <div class="card mb-3">
            <div class="card-header">Generate Receipt</div>
            <div class="card-body">
                <form method="POST" action="{{ route('claims.receipt', $claim) }}">
                    @csrf
                    <div class="mb-2">
                        <input type="number" step="0.01" name="quantity_collected" class="form-control form-control-sm @error('quantity_collected') is-invalid @enderror" placeholder="Quantity Collected" value="{{ old('quantity_collected') }}" required>
                        @error('quantity_collected')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-2">
                        <select name="unit" class="form-select form-select-sm @error('unit') is-invalid @enderror" required>
                            <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>kg</option>
                            <option value="litres" {{ old('unit') == 'litres' ? 'selected' : '' }}>litres</option>
                            <option value="items" {{ old('unit') == 'items' ? 'selected' : '' }}>items</option>
                            <option value="boxes" {{ old('unit') == 'boxes' ? 'selected' : '' }}>boxes</option>
                        </select>
                        @error('unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-2">
                        <input type="text" name="collected_by" class="form-control form-control-sm @error('collected_by') is-invalid @enderror" placeholder="Collected By" value="{{ old('collected_by') }}" required>
                        @error('collected_by')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-2">
                        <textarea name="condition_notes" class="form-control form-control-sm @error('condition_notes') is-invalid @enderror" rows="2" placeholder="Condition Notes">{{ old('condition_notes') }}</textarea>
                        @error('condition_notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-outline-success btn-sm w-100">Generate Receipt</button>
                </form>
            </div>
        </div>
        @endif

        <!-- Distribution Log Form -->
        @if($claim->status === 'collected')
        <div class="card mb-3">
            <div class="card-header">Log Distribution (SDG)</div>
            <div class="card-body">
                <form method="POST" action="{{ route('claims.distribution', $claim) }}">
                    @csrf
                    <div class="mb-2">
                        <input type="number" name="beneficiaries_count" class="form-control form-control-sm @error('beneficiaries_count') is-invalid @enderror" placeholder="Beneficiaries Count" value="{{ old('beneficiaries_count') }}" required min="1">
                        @error('beneficiaries_count')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-2">
                        <input type="text" name="distribution_location" class="form-control form-control-sm @error('distribution_location') is-invalid @enderror" placeholder="Distribution Location" value="{{ old('distribution_location') }}" required>
                        @error('distribution_location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-2">
                        <input type="number" step="0.01" name="quantity_distributed" class="form-control form-control-sm @error('quantity_distributed') is-invalid @enderror" placeholder="Quantity" value="{{ old('quantity_distributed') }}" required>
                        @error('quantity_distributed')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-2">
                        <select name="unit" class="form-select form-select-sm @error('unit') is-invalid @enderror" required>
                            <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>kg</option>
                            <option value="litres" {{ old('unit') == 'litres' ? 'selected' : '' }}>litres</option>
                            <option value="items" {{ old('unit') == 'items' ? 'selected' : '' }}>items</option>
                        </select>
                        @error('unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-2">
                        <textarea name="notes" class="form-control form-control-sm @error('notes') is-invalid @enderror" rows="2" placeholder="Notes">{{ old('notes') }}</textarea>
                        @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-outline-info btn-sm w-100">Submit Log</button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
