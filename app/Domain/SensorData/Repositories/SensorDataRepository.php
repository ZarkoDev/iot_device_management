<?php

declare(strict_types=1);

namespace App\Domain\SensorData\Repositories;

use App\Domain\Device\Models\Device;
use App\Domain\SensorData\Contracts\SensorDataRepositoryInterface;
use App\Domain\SensorData\Models\SensorData;
use App\Domain\User\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Eloquent implementation of SensorDataRepositoryInterface.
 *
 * This repository handles all sensor data access operations using Eloquent ORM.
 * Implements the Repository pattern for clean separation of data access logic.
 */
class SensorDataRepository implements SensorDataRepositoryInterface
{
    public function findById(int $id): ?SensorData
    {
        return SensorData::find($id);
    }

    public function findByDevice(Device $device): Collection
    {
        return SensorData::where('device_id', $device->id)
            ->orderBy('recorded_at', 'desc')
            ->get();
    }

    public function paginateByDevice(Device $device, int $perPage = 15): LengthAwarePaginator
    {
        return SensorData::where('device_id', $device->id)
            ->orderBy('recorded_at', 'desc')
            ->paginate($perPage);
    }

    public function findByUser(User $user): Collection
    {
        return SensorData::whereHas('device', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->orderBy('recorded_at', 'desc')->get();
    }

    public function paginateByUser(User $user, int $perPage = 15): LengthAwarePaginator
    {
        return SensorData::whereHas('device', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->orderBy('recorded_at', 'desc')->paginate($perPage);
    }

    public function create(array $data): SensorData
    {
        return SensorData::create($data);
    }

    public function getLatestByDevice(Device $device): ?SensorData
    {
        return SensorData::where('device_id', $device->id)
            ->orderBy('recorded_at', 'desc')
            ->first();
    }

    public function getStatistics(Device $device): array
    {
        $data = SensorData::where('device_id', $device->id);

        return [
            'total_readings' => $data->count(),
            'average_temperature' => $data->avg('temperature'),
            'min_temperature' => $data->min('temperature'),
            'max_temperature' => $data->max('temperature'),
            'latest_reading' => $this->getLatestByDevice($device),
        ];
    }
}
