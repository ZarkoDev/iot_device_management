<?php

declare(strict_types=1);

namespace App\Domain\SensorData\Actions;

use App\Domain\Device\Models\Device;
use App\Domain\SensorData\Contracts\SensorDataRepositoryInterface;
use App\Domain\SensorData\Models\SensorData;

/**
 * Action for recording sensor data.
 *
 * This action encapsulates the business logic for sensor data recording
 */
class RecordSensorDataAction
{
    public function __construct(
        private readonly SensorDataRepositoryInterface $sensorDataRepository
    ) {}

    /**
     * Execute the sensor data recording action.
     *
     * @param Device $device The device recording the data
     * @param float $temperature The temperature reading
     * @param \DateTime|null $recordedAt Optional timestamp (defaults to now)
     * @return SensorData The recorded sensor data
     */
    public function execute(Device $device, float $temperature, ?\DateTime $recordedAt = null): SensorData
    {
        $recordedAt = $recordedAt ?? new \DateTime();

        $sensorData = $this->sensorDataRepository->create([
            'device_id' => $device->id,
            'temperature' => $temperature,
            'recorded_at' => $recordedAt,
        ]);

        return $sensorData;
    }
}
