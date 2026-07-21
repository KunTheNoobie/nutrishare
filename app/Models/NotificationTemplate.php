<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * NotificationTemplate Model — Predefined notification templates (Module 1).
 */
class NotificationTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject',
        'body',
        'channel',
    ];

    /** Notifications dispatched using this template. */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Render the template body with dynamic placeholders.
     *
     * @param array $data Key-value pairs, e.g. ['donor_name' => 'John']
     */
    public function render(array $data): string
    {
        $body = $this->body;
        foreach ($data as $key => $value) {
            $body = str_replace('{' . $key . '}', e($value), $body);
        }
        return $body;
    }
}
