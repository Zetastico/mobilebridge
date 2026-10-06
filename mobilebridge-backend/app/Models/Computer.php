<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Computer extends Model
{
    use HasFactory;

    public const HEARTBEAT_TIMEOUT_SECONDS = 60;

    protected $fillable = [
        'user_id',
        'device_uuid',
        'name',
        'description',
        'local_ip',
        'local_port',
        'status',
        'last_seen_at',
        'capabilities',
    ];

    protected $casts = [
        'capabilities' => 'array',
        'last_seen_at' => 'datetime',
        'local_port' => 'integer',
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
     * Scope for genuinely online computers.
     */
    public function scopeOnline(Builder $query): Builder
    {
        $threshold = Carbon::now()->subSeconds(self::HEARTBEAT_TIMEOUT_SECONDS);
        return $query->where('status', 'online')
                     ->whereNotNull('last_seen_at')
                     ->where('last_seen_at', '>=', $threshold);
    }

    /**
     * Scope for offline computers.
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
    public function recordHeartbeat(?string $ip = null, ?int $port = null, ?array $capabilities = null): void
    {
        $update = [
            'status' => 'online',
            'last_seen_at' => now(),
        ];
        if ($ip !== null) {
            $update['local_ip'] = $ip;
        }
        if ($port !== null) {
            $update['local_port'] = $port;
        }
        if ($capabilities !== null) {
            $update['capabilities'] = $capabilities;
        }

        $this->update($update);
    }

    /**
     * Mark computer offline.
     */
    public function markOffline(): void
    {
        $this->update([
            'status' => 'offline',
            'last_seen_at' => now(),
        ]);
    }
}
