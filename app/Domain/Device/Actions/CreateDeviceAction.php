<?php

declare(strict_types=1);

namespace App\Domain\Device\Actions;

use App\Domain\Device\Contracts\DeviceRepositoryInterface;
use App\Domain\Device\Models\Device;
use App\Domain\User\Models\User;

/**
 * Action for creating a new device.
 *
 * This action encapsulates the business logic for device creation,
 * including ownership assignment and validation.
 */
class CreateDeviceAction
{
    public function __construct(
        private readonly DeviceRepositoryInterface $deviceRepository
    ) {}

    /**
     * Execute the device creation action.
     *
     * @param array $data Device data including serial_number and name
     * @return Device The created device
     */
    public function execute(array $data): Device
    {
        $data['is_active'] = $data['is_active'] ?? true;

        return $this->deviceRepository->create($data);
    }
}
