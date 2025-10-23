<?php

declare(strict_types=1);

namespace App\Domain\SensorData\Contracts;

use App\Domain\Device\Models\Device;
use App\Domain\SensorData\Models\SensorData;
use App\Domain\User\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Repository contract for SensorData domain operations.
 *
 * This interface defines the contract for sensor data access operations,
 * ensuring proper data isolation and efficient querying.
 */
interface SensorDataRepositoryInterface
{
    /**
     * Find sensor data by ID.
     */
    public function findById(int $id): ?SensorData;

    /**
     * Get sensor data for a specific device.
     */
    public function findByDevice(Device $device): Collection;

    /**
     * Get paginated sensor data for a specific device.
     */
    public function paginateByDevice(Device $device, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get sensor data for a specific user (all their devices).
     */
    public function findByUser(User $user): Collection;

    /**
     * Get paginated sensor data for a specific user.
     */
    public function paginateByUser(User $user, int $perPage = 15): LengthAwarePaginator;

    /**
     * Create new sensor data.
     */
    public function create(array $data): SensorData;
    /**
     * Get latest sensor data for a device.
     */
    public function getLatestByDevice(Device $device): ?SensorData;

    /**
     * Get sensor data statistics for a device.
     */
    public function getStatistics(Device $device): array;
}
