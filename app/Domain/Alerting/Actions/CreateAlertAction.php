<?php

declare(strict_types=1);

namespace App\Domain\Alerting\Actions;

use App\Domain\Alerting\Services\AlertService;
use App\Domain\SensorData\Models\SensorData;
use Illuminate\Support\Collection;
use App\Domain\Device\Models\Device;

/**
 * Action for creating alerts based on sensor data.
 *
 * This action encapsulates the business logic for alert creation,
 * delegating to the AlertService for evaluation and creation.
 */
class CreateAlertAction
{
    public function __construct(
        private readonly AlertService $alertService
    ) {}

    /**
     * Execute the alert creation action for sensor data.
     *
     * @param SensorData $sensorData The sensor data that triggered the alert
     * @return Collection The created alerts
     */
    public function execute(SensorData $sensorData): Collection
    {
        return $this->alertService->evaluateSensorData($sensorData);
    }

    /**
     * Execute the alert creation action for device offline detection.
     *
     * @param Device $device
     * @return Collection
     */
    public function executeForDeviceOffline(Device $device): Collection
    {
        $alerts = collect();

        $offlineAlert = $this->alertService->checkDeviceOffline($device);
        if ($offlineAlert !== null) {
            $alerts->push($offlineAlert);
        }

        return $alerts;
    }
}
