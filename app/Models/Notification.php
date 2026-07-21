<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Notification Model — Dispatched alerts to users (Module 1).
 * Created by the Observer Pattern when donations/claims change state.
 */
class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'notification_template_id',
        'donation_id',
        'title',
        'message',
        'channel',
        'is_read',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'sent_at' => 'datetime',
        ];
    }

    /** The user who received this notification. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** The template used for this notification. */
    public function notificationTemplate(): BelongsTo
    {
        return $this->belongsTo(NotificationTemplate::class);
    }

    /** The donation related to this notification. */
    public function donation(): BelongsTo
    {
        return $this->belongsTo(Donation::class);
    }

    /** Scope: unread notifications. */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}
