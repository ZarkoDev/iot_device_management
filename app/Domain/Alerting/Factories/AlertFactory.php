<?php

declare(strict_types=1);

namespace App\Domain\Alerting\Factories;

use App\Domain\Alerting\Enums\AlertSeverity;
use App\Domain\Alerting\Enums\AlertType;
use App\Domain\Alerting\Models\Alert;
use App\Domain\Alerting\ValueObjects\AlertCondition;
use App\Domain\Device\Models\Device;
use App\Domain\SensorData\Models\SensorData;

/**
 * Factory for creating different types of alerts.
 *
 * This factory encapsulates the logic for creating alerts based on
 * different conditions and alert types, ensuring consistent alert creation.
 */
class AlertFactory
{
    /**
     * Create a temperature warning alert.
     */
    public function createTemperatureWarning(
        Device $device,
        ?SensorData $sensorData,
        float $temperature
    ): Alert {
        $condition = AlertCondition::temperatureWarning();

        return new Alert([
            'device_id' => $device->id,
            'sensor_data_id' => $sensorData?->id,
            'alert_type' => AlertType::TEMPERATURE_WARNING->value,
            'severity' => AlertSeverity::WARNING->value,
            'message' => $this->generateTemperatureMessage($device, $temperature, $condition),
            'temperature' => $temperature,
            'threshold_min' => $condition->minThreshold,
            'threshold_max' => $condition->maxThreshold,
            'condition_name' => $condition->name,
            'condition_description' => $condition->description,
        ]);
    }

    /**
     * Create a critical low temperature alert.
     */
    public function createCriticalLowTemperature(
        Device $device,
        ?SensorData $sensorData,
        float $temperature
    ): Alert {
        $condition = AlertCondition::criticalLowTemperature();

        return new Alert([
            'device_id' => $device->id,
            'sensor_data_id' => $sensorData?->id,
            'alert_type' => AlertType::TEMPERATURE_CRITICAL_LOW->value,
            'severity' => AlertSeverity::CRITICAL->value,
            'message' => $this->generateTemperatureMessage($device, $temperature, $condition),
            'temperature' => $temperature,
            'threshold_min' => $condition->minThreshold,
            'threshold_max' => null,
            'condition_name' => $condition->name,
            'condition_description' => $condition->description,
        ]);
    }

    /**
     * Create a critical high temperature alert.
     */
    public function createCriticalHighTemperature(
        Device $device,
        ?SensorData $sensorData,
        float $temperature
    ): Alert {
        $condition = AlertCondition::criticalHighTemperature();

        return new Alert([
            'device_id' => $device->id,
            'sensor_data_id' => $sensorData?->id,
            'alert_type' => AlertType::TEMPERATURE_CRITICAL_HIGH->value,
            'severity' => AlertSeverity::CRITICAL->value,
            'message' => $this->generateTemperatureMessage($device, $temperature, $condition),
            'temperature' => $temperature,
            'threshold_min' => null,
            'threshold_max' => $condition->maxThreshold,
            'condition_name' => $condition->name,
            'condition_description' => $condition->description,
        ]);
    }

    /**
     * Create a sensor offline alert.
     */
    public function createSensorOffline(
        Device $device,
        int $timeoutMinutes = 15
    ): Alert {
        $condition = AlertCondition::sensorOffline($timeoutMinutes);

        return new Alert([
            'device_id' => $device->id,
            'sensor_data_id' => null,
            'alert_type' => AlertType::SENSOR_OFFLINE->value,
            'severity' => AlertSeverity::CRITICAL->value,
            'message' => $this->generateOfflineMessage($device, $timeoutMinutes),
            'temperature' => null,
            'threshold_min' => null,
            'threshold_max' => null,
            'condition_name' => $condition->name,
            'condition_description' => $condition->description,
            'timeout_minutes' => $timeoutMinutes,
        ]);
    }

    /**
     * Generate a temperature-based alert message.
     */
    private function generateTemperatureMessage(
        Device $device,
        float $temperature,
        AlertCondition $condition
    ): string {
        $deviceName = $device->name;
        $formattedTemp = number_format($temperature, 1);

        if ($condition->minThreshold !== null && $temperature < $condition->minThreshold) {
            return "Temperature alert for device '{$deviceName}': {$formattedTemp}°C is below minimum threshold ({$condition->minThreshold}°C)";
        }

        if ($condition->maxThreshold !== null && $temperature > $condition->maxThreshold) {
            return "Temperature alert for device '{$deviceName}': {$formattedTemp}°C is above maximum threshold ({$condition->maxThreshold}°C)";
        }

        return "Temperature alert for device '{$deviceName}': {$formattedTemp}°C is outside normal range";
    }

    /**
     * Generate an offline alert message.
     */
    private function generateOfflineMessage(Device $device, int $timeoutMinutes): string
    {
        $deviceName = $device->name;
        return "Device '{$deviceName}' has been offline for more than {$timeoutMinutes} minutes";
    }
}
