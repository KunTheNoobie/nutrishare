<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * FoodItem Model — Individual food items linked to donations (Module 4).
 * Has a Many-to-Many relationship with AllergenTags.
 */
class FoodItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'donation_id',
        'inventory_location_id',
        'category_id',
        'name',
        'description',
        'quantity',
        'unit',
        'expiry_date',
        'storage_requirements',
        'is_perishable',
        'image_paths',
    ];

    protected function casts(): array
    {
        return [
            'expiry_date' => 'datetime',
            'quantity' => 'decimal:2',
            'is_perishable' => 'boolean',
            'image_paths' => 'array',
        ];
    }

    /**
     * Get image paths accessor with fallback to linked donation images.
     */
    public function getImagePathsAttribute($value): array
    {
        if ($value) {
            $decoded = is_string($value) ? json_decode($value, true) : $value;
            if (is_array($decoded) && !empty($decoded)) {
                return $decoded;
            }
        }
        return $this->donation ? ($this->donation->image_paths ?? []) : [];
    }

    // ──────────────── Relationships ────────────────

    /** The donation this food item belongs to. */
    public function donation(): BelongsTo
    {
        return $this->belongsTo(Donation::class);
    }

    /** The inventory location where this item is stored. */
    public function inventoryLocation(): BelongsTo
    {
        return $this->belongsTo(InventoryLocation::class);
    }

    /** The category of this food item. */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Allergen tags associated with this food item.
     * Many-to-Many relationship via allergen_tag_food_item pivot table.
     */
    public function allergenTags(): BelongsToMany
    {
        return $this->belongsToMany(AllergenTag::class)->withTimestamps();
    }

    /** Check if the food item has expired. */
    public function isExpired(): bool
    {
        return $this->expiry_date->isPast();
    }
}
