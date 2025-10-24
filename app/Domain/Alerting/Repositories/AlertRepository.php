<?php

declare(strict_types=1);

namespace App\Domain\Alerting\Repositories;

use App\Domain\Alerting\Contracts\AlertRepositoryInterface;
use App\Domain\Alerting\Models\Alert;
use App\Domain\Device\Models\Device;
use App\Domain\User\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Eloquent implementation of AlertRepositoryInterface.
 *
 * This repository handles all alert data access operations using Eloquent ORM.
 * Implements the Repository pattern for clean separation of data access logic.
 */
class AlertRepository implements AlertRepositoryInterface
{
    public function findById(int $id): ?Alert
    {
        return Alert::find($id);
    }

    public function findByDevice(Device $device): Collection
    {
        return Alert::where('device_id', $device->id)
            ->active()
            ->latest()
            ->get();
    }

    public function paginateByDevice(Device $device, int $perPage = 15): LengthAwarePaginator
    {
        return Alert::where('device_id', $device->id)
            ->active()
            ->latest()
            ->paginate($perPage);
    }

    public function findByUser(User $user, int $alertId): ?Alert
    {
        return Alert::whereHas('device', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->where('id', $alertId)
            ->first();
    }

    public function paginateByUser(User $user, int $perPage = 15): LengthAwarePaginator
    {
        return Alert::whereHas('device', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->active()
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data): Alert
    {
        return Alert::create($data);
    }

    public function update(Alert $alert, array $data): Alert
    {
        $alert->update($data);
        return $alert->fresh();
    }

    public function delete(Alert $alert): bool
    {
        return $alert->delete();
    }

    public function resolve(Alert $alert): Alert
    {
        $alert->resolve();
        return $alert->fresh();
    }

    public function getByType(string $alertType): Collection
    {
        return Alert::where('alert_type', $alertType)
            ->active()
            ->latest()
            ->get();
    }
}
