<?php

declare(strict_types=1);

namespace App\Domain\Device\Models;

use App\Domain\User\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Device model representing an IoT temperature sensor.
 *
 * Devices can be transferred between users and maintain their data history.
 * Each device belongs to exactly one user at any given time.
 */
class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial_number',
        'name',
        'user_id',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the user who owns this device.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if the device is currently active.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Transfer device ownership to another user.
     */
    public function transferTo(User $newOwner): void
    {
        $this->update(['user_id' => $newOwner->id]);
    }
}
