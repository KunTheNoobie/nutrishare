<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * InventoryLocation Model — Storage facilities (Module 4).
 */
class InventoryLocation extends Model
{
    use HasFactory, \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'address',
        'storage_type',
        'capacity',
        'current_occupancy',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'capacity' => 'decimal:2',
            'current_occupancy' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /** The user who owns this inventory location. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Food items stored at this location. */
    public function foodItems(): HasMany
    {
        return $this->hasMany(FoodItem::class);
    }

    /** Calculate available capacity. */
    public function availableCapacity(): float
    {
        if (!$this->capacity) return 0;
        return max(0, (float) $this->capacity - (float) $this->current_occupancy);
    }
}
