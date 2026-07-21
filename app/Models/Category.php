<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Category Model — Food item classification (Module 4).
 * e.g., Dairy, Produce, Bakery, Canned Goods.
 */
class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    /** Food items in this category. */
    public function foodItems(): HasMany
    {
        return $this->hasMany(FoodItem::class);
    }
}
