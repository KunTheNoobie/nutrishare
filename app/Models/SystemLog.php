<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * SystemLog Model — Audit trail for platform actions (Module 1/4).
 * SECURITY: Log injection prevention via CRLF sanitization helper.
 */
class SystemLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip_address',
        'user_agent',
        'level',
    ];

    /** The user who performed the action. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * SECURITY (Module 4): Sanitize description to prevent log injection.
     * Strips CRLF characters (\r\n) before storing.
     */
    public function setDescriptionAttribute(string $value): void
    {
        $this->attributes['description'] = \App\Helpers\SecurityHelper::sanitizeLogInput($value);
    }

    /**
     * SECURITY (Module 4): Sanitize action field.
     */
    public function setActionAttribute(string $value): void
    {
        $this->attributes['action'] = \App\Helpers\SecurityHelper::sanitizeLogInput($value);
    }
}
