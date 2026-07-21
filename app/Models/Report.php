<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Report Model — Analytics and SDG impact reports.
 */
class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'type',
        'report_date',
    ];

    protected function casts(): array
    {
        return [
            'report_date' => 'date',
        ];
    }

    /** The user who generated this report. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
