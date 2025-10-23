<?php

declare(strict_types=1);

namespace App\Domain\SensorData\Models;

use App\Domain\Device\Models\Device;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * SensorData model representing temperature measurements from IoT devices.
 *
 * This model stores temperature readings with timestamps and device association.
 * Data is immutable once created to maintain audit trail.
 */
class SensorData extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'temperature',
        'recorded_at',
    ];

    protected function casts(): array
    {
        return [
            'temperature' => 'float',
            'recorded_at' => 'datetime',
        ];
    }

    /**
     * Get the device that recorded this sensor data.
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    /**
     * Get the temperature in a formatted string.
     */
    public function getFormattedTemperatureAttribute(): string
    {
        return number_format($this->temperature, 2) . '°C';
    }

    /**
     * Check if this temperature reading is within normal range.
     * Normal range is defined as 0-30 degrees Celsius.
     */
    public function isWithinNormalRange(): bool
    {
        return $this->temperature >= config('sensors.temperature.min')
            && $this->temperature <= config('sensors.temperature.max');
    }

}
