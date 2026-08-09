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
     * Automatically capture IP address and User Agent when creating a log.
     */
    protected static function booted(): void
    {
        static::creating(function ($log) {
            if (empty($log->ip_address) && request()) {
                $log->ip_address = request()->ip() ?: '127.0.0.1';
            }
            if (empty($log->user_agent) && request()) {
                $log->user_agent = request()->userAgent() ?: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)';
            }
        });
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
