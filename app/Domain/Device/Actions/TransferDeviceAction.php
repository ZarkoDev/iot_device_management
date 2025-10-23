<?php

declare(strict_types=1);

namespace App\Domain\Device\Actions;

use App\Domain\Device\Contracts\DeviceRepositoryInterface;
use App\Domain\Device\Models\Device;
use App\Domain\User\Models\User;

/**
 * Action for transferring device ownership.
 * 
 * This action encapsulates the business logic for device ownership transfer,
 * ensuring proper validation and data integrity.
 */
class TransferDeviceAction
{
    public function __construct(
        private readonly DeviceRepositoryInterface $deviceRepository
    ) {}

    /**
     * Execute the device transfer action.
     * 
     * @param Device $device The device to transfer
     * @param User $newOwner The new owner of the device
     * @return Device The updated device
     */
    public function execute(Device $device, User $newOwner): Device
    {
        return $this->deviceRepository->transferOwnership($device, $newOwner);
    }
}
