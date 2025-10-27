<?php

declare(strict_types=1);

namespace App\Domain\Device\Actions;

use App\Domain\Device\Contracts\DeviceRepositoryInterface;
use App\Domain\Device\Models\Device;
use App\Domain\User\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Action for attaching device ownership.
 *
 * This action encapsulates the business logic for device ownership attach,
 * ensuring proper validation and data integrity.
 */
class AttachDeviceAction
{
    public function __construct(
        private readonly DeviceRepositoryInterface $deviceRepository
    ) {}

    /**
     * Execute the device attach action.
     *
     * @param Device $device The device to attach
     * @param User $newOwner The new owner of the device
     * @return Device The updated device
     */
    public function execute(Device $device, User $newOwner): Device
    {
        return DB::transaction(function () use ($device, $newOwner) {
            // Clean up existing data before attaching new user
            $device->sensorData()->delete();
            $device->alerts()->delete();

            return $this->deviceRepository->attach($device, $newOwner);
        });
    }
}
