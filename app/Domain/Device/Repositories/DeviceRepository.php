<?php

declare(strict_types=1);

namespace App\Domain\Device\Repositories;

use App\Domain\Device\Contracts\DeviceRepositoryInterface;
use App\Domain\Device\Models\Device;
use App\Domain\User\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Eloquent implementation of DeviceRepositoryInterface.
 *
 * This repository handles all device data access operations using Eloquent ORM.
 * Implements the Repository pattern for clean separation of data access logic.
 */
class DeviceRepository implements DeviceRepositoryInterface
{
    public function findById(int $id): ?Device
    {
        return Device::find($id);
    }

    public function findBySerialNumber(string $serialNumber): ?Device
    {
        return Device::where('serial_number', $serialNumber)->first();
    }

    public function findByUser(User $user, int $deviceId): ?Device
    {
        return Device::where('user_id', $user->id)
            ->where('id', $deviceId)
            ->first();
    }

    public function paginateByUser(User $user, int $perPage = 15): LengthAwarePaginator
    {
        return Device::where('user_id', $user->id)->active()->paginate($perPage);
    }

    public function create(array $data): Device
    {
        return Device::create($data);
    }

    public function update(Device $device, array $data): Device
    {
        $device->update($data);
        return $device->fresh();
    }

    public function delete(Device $device): bool
    {
        return $device->delete();
    }

    public function transferOwnership(Device $device, User $newOwner): Device
    {
        $device->transferTo($newOwner);
        return $device->fresh();
    }

    public function getActiveDevices(): Collection
    {
        return Device::where('is_active', true)->get();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Device::paginate($perPage);
    }
}
