<?php

declare(strict_types=1);

namespace App\Domain\Alerting\Contracts;

use App\Domain\Alerting\Models\Alert;
use App\Domain\Device\Models\Device;
use App\Domain\User\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Repository contract for Alert domain operations.
 *
 * This interface defines the contract for alert data access operations,
 * ensuring proper data isolation and efficient alert management.
 */
interface AlertRepositoryInterface
{
    /**
     * Find an alert by ID.
     */
    public function findById(int $id): ?Alert;

    /**
     * Get alerts for a specific device.
     */
    public function findByDevice(Device $device): Collection;

    /**
     * Get paginated alerts for a specific device.
     */
    public function paginateByDevice(Device $device, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get alerts for a specific user (all their devices).
     */
    public function findByUser(User $user, int $alertId): ?Alert;

    /**
     * Get paginated alerts for a specific user.
     */
    public function paginateByUser(User $user, int $perPage = 15): LengthAwarePaginator;

    /**
     * Create a new alert.
     */
    public function create(array $data): Alert;

    /**
     * Update an existing alert.
     */
    public function update(Alert $alert, array $data): Alert;

    /**
     * Delete an alert.
     */
    public function delete(Alert $alert): bool;

    /**
     * Resolve an alert.
     */
    public function resolve(Alert $alert): Alert;

    /**
     * Get alerts by type.
     */
    public function getByType(string $alertType): Collection;
}
