<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Vehicle Model — Assigned to claims for food collection logistics (Module 3).
 */
class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'claim_id',
        'plate_number',
        'vehicle_type',
        'driver_name',
        'driver_phone',
        'capacity_kg',
    ];

    protected function casts(): array
    {
        return [
            'capacity_kg' => 'decimal:2',
        ];
    }

    /** The claim this vehicle is assigned to. */
    public function claim(): BelongsTo
    {
        return $this->belongsTo(Claim::class);
    }
}
