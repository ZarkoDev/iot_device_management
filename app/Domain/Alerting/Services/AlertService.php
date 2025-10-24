<?php

declare(strict_types=1);

namespace App\Domain\Alerting\Services;

use App\Domain\Alerting\Contracts\AlertRepositoryInterface;
use App\Domain\Alerting\Enums\AlertType;
use App\Domain\Alerting\Factories\AlertFactory;
use App\Domain\Alerting\Models\Alert;
use App\Domain\Alerting\ValueObjects\AlertCondition;
use App\Domain\Device\Models\Device;
use App\Domain\SensorData\Models\SensorData;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

/**
 * Service for managing alert evaluation and creation.
 *
 * This service orchestrates the evaluation of different alert conditions
 * and creates appropriate alerts based on sensor data and device status.
 */
class AlertService
{
    public function __construct(
        private readonly AlertRepositoryInterface $alertRepository,
        private readonly AlertFactory $alertFactory
    ) {}

    /**
     * Evaluate sensor data and create alerts if conditions are met.
     */
    public function evaluateSensorData(SensorData $sensorData): Collection
    {
        $alerts = collect();
        $temperature = $sensorData->temperature;
        $device = $sensorData->device;

        // Check critical high temperature condition
        if ($this->shouldCreateCriticalHighTemperature($temperature)) {
            $alert = $this->alertFactory->createCriticalHighTemperature($device, $sensorData, $temperature);
            $alerts->push($this->alertRepository->create($alert->toArray()));
        }
        // Check critical low temperature condition
        elseif ($this->shouldCreateCriticalLowTemperature($temperature)) {
            $alert = $this->alertFactory->createCriticalLowTemperature($device, $sensorData, $temperature);
            $alerts->push($this->alertRepository->create($alert->toArray()));
        }
        // Check temperature warning condition
        elseif ($this->shouldCreateTemperatureWarning($temperature)) {
            $alert = $this->alertFactory->createTemperatureWarning($device, $sensorData, $temperature);
            $alerts->push($this->alertRepository->create($alert->toArray()));
        }

        // Push if there are other alerts that shouldn't be in elseif

        return $alerts;
    }

    /**
     * Check if a device is offline and create an alert if needed.
     */
    public function checkDeviceOffline(Device $device): ?Alert
    {
        $timeoutMinutes = config('sensors.alert_timeout_minutes');
        $condition = AlertCondition::sensorOffline($timeoutMinutes);

        $lastSeenMinutesAgo = $this->getLastSeenMinutesAgo($device);

        if ($condition->isOfflineMet($lastSeenMinutesAgo)) {
            $alert = $this->alertFactory->createSensorOffline($device, $timeoutMinutes);
            return $this->alertRepository->create($alert->toArray());
        }

        return null;
    }

    /**
     * Check if temperature warning should be created.
     */
    private function shouldCreateTemperatureWarning(?float $temperature): bool
    {
        if ($temperature === null) {
            return false;
        }

        $condition = AlertCondition::temperatureWarning();
        return $condition->isMet($temperature);
    }

    /**
     * Check if critical low temperature alert should be created.
     */
    private function shouldCreateCriticalLowTemperature(?float $temperature): bool
    {
        if ($temperature === null) {
            return false;
        }

        $condition = AlertCondition::criticalLowTemperature();
        return $condition->isMet($temperature);
    }

    /**
     * Check if critical high temperature alert should be created.
     */
    private function shouldCreateCriticalHighTemperature(?float $temperature): bool
    {
        if ($temperature === null) {
            return false;
        }

        $condition = AlertCondition::criticalHighTemperature();
        return $condition->isMet($temperature);
    }

    /**
     * Get the number of minutes since the device was last seen.
     */
    private function getLastSeenMinutesAgo(Device $device): ?int
    {
        $lastSensorData = $device->sensorData()->latest('recorded_at')->first();

        if (!$lastSensorData) {
            return null;
        }

        return (int) $lastSensorData->recorded_at->diffInMinutes(now());
    }
}
