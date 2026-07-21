<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * DistributionLog Model — SDG impact tracking (Module 3).
 * Records how claimed food was distributed to beneficiaries.
 */
class DistributionLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'claim_id',
        'beneficiaries_count',
        'distribution_location',
        'notes',
        'quantity_distributed',
        'unit',
        'distributed_at',
    ];

    protected function casts(): array
    {
        return [
            'distributed_at' => 'datetime',
            'quantity_distributed' => 'decimal:2',
        ];
    }

    /** The claim this distribution log belongs to. */
    public function claim(): BelongsTo
    {
        return $this->belongsTo(Claim::class);
    }
}
