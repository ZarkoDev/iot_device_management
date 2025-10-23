<?php

declare(strict_types=1);

namespace App\Domain\Device\Contracts;

use App\Domain\Device\Models\Device;
use App\Domain\User\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Repository contract for Device domain operations.
 * 
 * This interface defines the contract for device data access operations,
 * ensuring proper device ownership and data isolation.
 */
interface DeviceRepositoryInterface
{
    /**
     * Find a device by ID.
     */
    public function findById(int $id): ?Device;

    /**
     * Find a device by serial number.
     */
    public function findBySerialNumber(string $serialNumber): ?Device;

    /**
     * Get all devices for a specific user.
     */
    public function findByUser(User $user): Collection;

    /**
     * Get paginated devices for a specific user.
     */
    public function paginateByUser(User $user, int $perPage = 15): LengthAwarePaginator;

    /**
     * Create a new device.
     */
    public function create(array $data): Device;

    /**
     * Update an existing device.
     */
    public function update(Device $device, array $data): Device;

    /**
     * Delete a device.
     */
    public function delete(Device $device): bool;

    /**
     * Transfer device ownership to another user.
     */
    public function transferOwnership(Device $device, User $newOwner): Device;

    /**
     * Get all active devices.
     */
    public function getActiveDevices(): Collection;

    /**
     * Get all devices with pagination.
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;
}
