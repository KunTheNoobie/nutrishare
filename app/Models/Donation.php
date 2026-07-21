<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Donation Model — Core donation entity (Module 1).
 * Subject in the Observer Pattern: notifies observers when created.
 * Lifecycle: available -> claimed -> collected -> completed | expired
 */
class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'quantity',
        'unit',
        'pickup_address',
        'latitude',
        'longitude',
        'expiry_date',
        'status',
        'image_path',
    ];

    protected function casts(): array
    {
        return [
            'expiry_date' => 'datetime',
            'quantity' => 'decimal:2',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    // ──────────────── Relationships ────────────────

    /** The donor who published this donation. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Alias for readability: the donor. */
    public function donor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Claims submitted for this donation. */
    public function claims(): HasMany
    {
        return $this->hasMany(Claim::class);
    }

    /** Food items associated with this donation. */
    public function foodItems(): HasMany
    {
        return $this->hasMany(FoodItem::class);
    }

    /** Notifications triggered by this donation. */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    // ──────────────── Scopes ────────────────

    /** Scope: only available (unclaimed) donations. */
    public function scopeActive($query)
    {
        return $query->where('status', 'available')
                     ->where('expiry_date', '>', now());
    }

    /** Scope: expired donations. */
    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<=', now())
                     ->where('status', 'available');
    }
}
