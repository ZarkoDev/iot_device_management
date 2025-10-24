<?php

declare(strict_types=1);

namespace App\Domain\Alerting\Enums;

/**
 * Enum representing different types of alerts that can be generated.
 * 
 * Each alert type has specific conditions and thresholds that determine
 * when an alert should be triggered.
 */
enum AlertType: string
{
    case TEMPERATURE_WARNING = 'temperature_warning';
    case TEMPERATURE_CRITICAL_LOW = 'temperature_critical_low';
    case TEMPERATURE_CRITICAL_HIGH = 'temperature_critical_high';
    case SENSOR_OFFLINE = 'sensor_offline';

    /**
     * Get a human-readable description of the alert type.
     */
    public function getDescription(): string
    {
        return match ($this) {
            self::TEMPERATURE_WARNING => 'Temperature Warning',
            self::TEMPERATURE_CRITICAL_LOW => 'Critical Low Temperature',
            self::TEMPERATURE_CRITICAL_HIGH => 'Critical High Temperature',
            self::SENSOR_OFFLINE => 'Sensor Offline',
        };
    }

    /**
     * Get the severity level for this alert type.
     */
    public function getSeverity(): AlertSeverity
    {
        return match ($this) {
            self::TEMPERATURE_WARNING => AlertSeverity::WARNING,
            self::TEMPERATURE_CRITICAL_LOW,
            self::TEMPERATURE_CRITICAL_HIGH,
            self::SENSOR_OFFLINE => AlertSeverity::CRITICAL,
        };
    }

    /**
     * Check if this alert type requires temperature data.
     */
    public function requiresTemperature(): bool
    {
        return match ($this) {
            self::TEMPERATURE_WARNING,
            self::TEMPERATURE_CRITICAL_LOW,
            self::TEMPERATURE_CRITICAL_HIGH => true,
            self::SENSOR_OFFLINE => false,
        };
    }

    /**
     * Check if this alert type requires sensor data.
     */
    public function requiresSensorData(): bool
    {
        return match ($this) {
            self::TEMPERATURE_WARNING,
            self::TEMPERATURE_CRITICAL_LOW,
            self::TEMPERATURE_CRITICAL_HIGH => true,
            self::SENSOR_OFFLINE => false,
        };
    }
}
