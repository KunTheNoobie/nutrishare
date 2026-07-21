<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * AllergenTag Model — Food safety allergen identifiers (Module 4).
 * Many-to-Many relationship with FoodItems.
 */
class AllergenTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'severity',
        'description',
    ];

    /**
     * Food items that have this allergen tag.
     * Many-to-Many relationship via allergen_tag_food_item pivot table.
     */
    public function foodItems(): BelongsToMany
    {
        return $this->belongsToMany(FoodItem::class)->withTimestamps();
    }
}
