<?php

declare(strict_types=1);

namespace App\Domain\Alerting\Models;

use App\Domain\Alerting\Enums\AlertSeverity;
use App\Domain\Alerting\Enums\AlertType;
use App\Domain\Device\Models\Device;
use App\Domain\SensorData\Models\SensorData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Alert model representing various types of alerts.
 *
 * Alerts are generated when specific conditions are met, including
 * temperature thresholds and device offline status.
 * This model supports extensible alert types for future requirements.
 */
class Alert extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'sensor_data_id',
        'alert_type',
        'severity',
        'message',
        'temperature',
        'threshold_min',
        'threshold_max',
        'condition_name',
        'condition_description',
        'timeout_minutes',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'temperature' => 'decimal:2',
            'threshold_min' => 'decimal:2',
            'threshold_max' => 'decimal:2',
            'timeout_minutes' => 'integer',
            'resolved_at' => 'datetime',
        ];
    }

    /**
     * Get the device that generated this alert.
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    /**
     * Get the sensor data that triggered this alert.
     */
    public function sensorData(): BelongsTo
    {
        return $this->belongsTo(SensorData::class);
    }

    /**
     * Return only active alerts
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('resolved_at');
    }

    /**
     * Return only resolved alerts
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeResolved(Builder $query): Builder
    {
        return $query->whereNotNull('resolved_at');
    }

    /**
     * Check if this alert is currently active (not resolved).
     */
    public function isActive(): bool
    {
        return !$this->resolved_at;
    }

    /**
     * Mark this alert as resolved.
     */
    public function resolve(): void
    {
        $this->update(['resolved_at' => now()]);
    }

    /**
     * Get the alert type enum.
     */
    public function getAlertType(): AlertType
    {
        return AlertType::from($this->alert_type);
    }

    /**
     * Get the alert severity enum.
     */
    public function getSeverity(): AlertSeverity
    {
        return AlertSeverity::from($this->severity);
    }

    /**
     * Check if this alert is critical.
     */
    public function isCritical(): bool
    {
        return $this->getSeverity()->isCritical();
    }

    /**
     * Check if this alert is a warning.
     */
    public function isWarning(): bool
    {
        return $this->getSeverity()->isWarning();
    }

    /**
     * Check if this alert is temperature-based.
     */
    public function isTemperatureAlert(): bool
    {
        return $this->getAlertType()->requiresTemperature();
    }

    /**
     * Check if this alert is for offline detection.
     */
    public function isOfflineAlert(): bool
    {
        return $this->alert_type === AlertType::SENSOR_OFFLINE->value;
    }

    /**
     * Get the priority for this alert.
     */
    public function getPriority(): string
    {
        return $this->getSeverity()->getPriority();
    }
}
