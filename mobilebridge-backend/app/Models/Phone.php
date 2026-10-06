<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Phone extends Model
{
    use HasFactory;

    public const HEARTBEAT_TIMEOUT_SECONDS = 60;

    protected $fillable = [
        'user_id',
        'device_uuid',
        'name',
        'model',
        'android_version',
        'status',
        'last_seen_at',
    ];

    protected $casts = [
        'last_seen_at' => 'datetime',
    ];

    protected $appends = [
        'is_online',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function connections(): HasMany
    {
        return $this->hasMany(Connection::class);
    }

    /**
     * Compute actual online status based on status and heartbeat freshness.
     */
    public function getIsOnlineAttribute(): bool
    {
        if ($this->status !== 'online' || !$this->last_seen_at) {
            return false;
        }

        return $this->last_seen_at->diffInSeconds(now()) <= self::HEARTBEAT_TIMEOUT_SECONDS;
    }

    /**
     * Scope for genuinely online phones.
     */
    public function scopeOnline(Builder $query): Builder
    {
        $threshold = Carbon::now()->subSeconds(self::HEARTBEAT_TIMEOUT_SECONDS);
        return $query->where('status', 'online')
                     ->whereNotNull('last_seen_at')
                     ->where('last_seen_at', '>=', $threshold);
    }

    /**
     * Scope for offline phones.
     */
    public function scopeOffline(Builder $query): Builder
    {
        $threshold = Carbon::now()->subSeconds(self::HEARTBEAT_TIMEOUT_SECONDS);
        return $query->where(function ($q) use ($threshold) {
            $q->where('status', '!=', 'online')
              ->orWhereNull('last_seen_at')
              ->orWhere('last_seen_at', '<', $threshold);
        });
    }

    /**
     * Mark heartbeat and online status.
     */
    public function recordHeartbeat(?string $name = null, ?string $model = null, ?string $androidVersion = null): void
    {
        $update = [
            'status' => 'online',
            'last_seen_at' => now(),
        ];
        if ($name !== null) {
            $update['name'] = $name;
        }
        if ($model !== null) {
            $update['model'] = $model;
        }
        if ($androidVersion !== null) {
            $update['android_version'] = $androidVersion;
        }

        $this->update($update);
    }

    /**
     * Mark phone offline.
     */
    public function markOffline(): void
    {
        $this->update([
            'status' => 'offline',
            'last_seen_at' => now(),
        ]);
    }
}
