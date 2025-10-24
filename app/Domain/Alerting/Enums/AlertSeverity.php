<?php

declare(strict_types=1);

namespace App\Domain\Alerting\Enums;

/**
 * Enum representing the severity level of an alert.
 *
 * Severity levels help prioritize alerts and determine appropriate
 * response actions.
 */
enum AlertSeverity: string
{
    case WARNING = 'warning';
    case CRITICAL = 'critical';

    /**
     * Get a human-readable description of the severity level.
     */
    public function getDescription(): string
    {
        return match ($this) {
            self::WARNING => 'Warning',
            self::CRITICAL => 'Critical',
        };
    }

    /**
     * Get the priority
     */
    public function getPriority(): string
    {
        return match ($this) {
            self::WARNING => 'low',
            self::CRITICAL => 'high',
        };
    }

    /**
     * Check if this severity level is critical.
     */
    public function isCritical(): bool
    {
        return $this === self::CRITICAL;
    }

    /**
     * Check if this severity level is a warning.
     */
    public function isWarning(): bool
    {
        return $this === self::WARNING;
    }
}
