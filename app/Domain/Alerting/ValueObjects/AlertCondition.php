<?php

declare(strict_types=1);

namespace App\Domain\Alerting\ValueObjects;

/**
 * Value object representing an alert condition.
 *
 * This immutable object encapsulates the logic for evaluating whether
 * an alert condition is met based on sensor data.
 */
readonly class AlertCondition
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly ?float $minThreshold = null,
        public readonly ?float $maxThreshold = null,
        public readonly ?int $timeoutMinutes = null,
    ) {}

    /**
     * Check if the condition is met based on temperature data.
     */
    public function isMet(?float $temperature): bool
    {
        if ($temperature === null) {
            return false;
        }

        // Check minimum threshold
        if ($this->minThreshold !== null && $temperature < $this->minThreshold) {
            return true;
        }

        // Check maximum threshold
        if ($this->maxThreshold !== null && $temperature > $this->maxThreshold) {
            return true;
        }

        return false;
    }

    /**
     * Check if the condition is met for offline detection.
     */
    public function isOfflineMet(?int $lastSeenMinutesAgo): bool
    {
        if ($this->timeoutMinutes === null || $lastSeenMinutesAgo === null) {
            return false;
        }

        return $lastSeenMinutesAgo > $this->timeoutMinutes;
    }

    /**
     * Get a human-readable description of the condition.
     */
    public function getDescription(): string
    {
        $parts = [];

        if ($this->minThreshold !== null) {
            $parts[] = "below {$this->minThreshold}°C";
        }

        if ($this->maxThreshold !== null) {
            $parts[] = "above {$this->maxThreshold}°C";
        }

        if ($this->timeoutMinutes !== null) {
            $parts[] = "offline for more than {$this->timeoutMinutes} minutes";
        }

        return implode(' or ', $parts);
    }

    /**
     * Create a temperature warning condition.
     */
    public static function temperatureWarning(): self
    {
        return new self(
            name: 'temperature_warning',
            description: 'Temperature outside normal range',
            minThreshold: config('sensors.temperature.min'),
            maxThreshold: config('sensors.temperature.max')
        );
    }

    /**
     * Create a critical low temperature condition.
     */
    public static function criticalLowTemperature(): self
    {
        return new self(
            name: 'critical_low_temperature',
            description: 'Critical low temperature',
            minThreshold: config('sensors.temperature.critical_min')
        );
    }

    /**
     * Create a critical high temperature condition.
     */
    public static function criticalHighTemperature(): self
    {
        return new self(
            name: 'critical_high_temperature',
            description: 'Critical high temperature',
            maxThreshold: config('sensors.temperature.critical_max')
        );
    }

    /**
     * Create a sensor offline condition.
     */
    public static function sensorOffline(int $timeoutMinutes = 15): self
    {
        return new self(
            name: 'sensor_offline',
            description: 'Sensor is offline or not responding',
            timeoutMinutes: $timeoutMinutes
        );
    }
}
