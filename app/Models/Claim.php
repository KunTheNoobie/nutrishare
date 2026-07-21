<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\States\Claim\ClaimState;
use App\States\Claim\PendingState;
use App\States\Claim\ApprovedState;
use App\States\Claim\CollectedState;

/**
 * Claim Model — NGO claims on donations (Module 3).
 * Uses the State Pattern for lifecycle management.
 * States: PendingState -> ApprovedState -> CollectedState
 */
class Claim extends Model
{
    use HasFactory, \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = [
        'donation_id',
        'user_id',
        'status',
        'justification',
        'pickup_scheduled_at',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'pickup_scheduled_at' => 'datetime',
        ];
    }

    // ──────────────── Relationships ────────────────

    /** The donation being claimed. */
    public function donation(): BelongsTo
    {
        return $this->belongsTo(Donation::class);
    }

    /** The NGO user who submitted this claim. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Alias for readability. */
    public function ngo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Vehicle assigned for pickup. */
    public function vehicle(): HasOne
    {
        return $this->hasOne(Vehicle::class);
    }

    /** Collection receipt generated upon collection. */
    public function collectionReceipt(): HasOne
    {
        return $this->hasOne(CollectionReceipt::class);
    }

    /** Distribution logs for SDG tracking. */
    public function distributionLogs(): HasMany
    {
        return $this->hasMany(DistributionLog::class);
    }

    // ──────────────── State Pattern ────────────────

    /**
     * Get the current state object for this claim.
     * Implements the State Pattern (Module 3).
     */
    public function getStateObject(): ClaimState
    {
        return match ($this->status) {
            'approved' => new ApprovedState($this),
            'collected' => new CollectedState($this),
            default => new PendingState($this),
        };
    }

    /**
     * Transition to the next state via the State Pattern.
     */
    public function transitionTo(string $action): bool
    {
        $state = $this->getStateObject();
        return $state->handle($action);
    }
}
