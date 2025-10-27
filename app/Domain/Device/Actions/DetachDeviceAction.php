<?php

declare(strict_types=1);

namespace App\Domain\Device\Actions;

use App\Domain\Device\Contracts\DeviceRepositoryInterface;
use App\Domain\Device\Models\Device;
use App\Domain\User\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Action for detaching device ownership.
 *
 * This action encapsulates the business logic for device ownership detach,
 * ensuring proper validation and data integrity.
 */
class DetachDeviceAction
{
    public function __construct(
        private readonly DeviceRepositoryInterface $deviceRepository
    ) {}

    /**
     * Execute the device detach action.
     *
     * @param Device $device The device to transfer
     * @return Device The updated device
     */
    public function execute(Device $device): Device
    {
        return DB::transaction(function () use ($device) {
            // Clean up existing data before detaching
            $device->sensorData()->delete();
            $device->alerts()->delete();

            return $this->deviceRepository->detach($device);
        });
    }
}
