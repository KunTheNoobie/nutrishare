<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * CollectionReceipt Model — Proof of food collection (Module 3).
 */
class CollectionReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'claim_id',
        'receipt_number',
        'quantity_collected',
        'unit',
        'collected_by',
        'condition_notes',
        'signature_path',
        'collected_at',
    ];

    protected function casts(): array
    {
        return [
            'collected_at' => 'datetime',
            'quantity_collected' => 'decimal:2',
        ];
    }

    /** The claim this receipt belongs to. */
    public function claim(): BelongsTo
    {
        return $this->belongsTo(Claim::class);
    }

    /**
     * Generate a unique receipt number.
     */
    public static function generateReceiptNumber(): string
    {
        return 'RCP-' . strtoupper(uniqid()) . '-' . date('Ymd');
    }
}
