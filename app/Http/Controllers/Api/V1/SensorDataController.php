<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Domain\Device\Contracts\DeviceRepositoryInterface;
use App\Domain\Device\Models\Device;
use App\Domain\SensorData\Actions\RecordSensorDataAction;
use App\Domain\SensorData\Contracts\SensorDataRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\SensorData\CreateSensorDataRequest;
use App\Http\Resources\Api\V1\SensorDataResource;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * API Controller for Sensor Data management.
 *
 * This controller handles sensor data-related API endpoints following RESTful conventions.
 * All business logic is delegated to Actions and Services.
 */
class SensorDataController extends Controller
{
    public function __construct(
        private readonly SensorDataRepositoryInterface $sensorDataRepository,
        private readonly DeviceRepositoryInterface $deviceRepository,
        private readonly RecordSensorDataAction $recordSensorDataAction
    ) {}

    /**
     * Display a listing of sensor data for the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $sensorData = $this->sensorDataRepository->paginateByUser($user, 15);

        return response()->json([
            'data' => SensorDataResource::collection($sensorData->items()),
            'meta' => [
                'current_page' => $sensorData->currentPage(),
                'last_page' => $sensorData->lastPage(),
                'per_page' => $sensorData->perPage(),
                'total' => $sensorData->total(),
            ],
        ]);
    }

    /**
     * Store newly recorded sensor data.
     */
    public function store(CreateSensorDataRequest $request): JsonResponse
    {
        $device = $this->deviceRepository->findBySerialNumber($request->input('device_serial'));

        if (!$device) {
            return response()->json(['message' => 'Device not found'], 404);
        }

        $sensorData = $this->recordSensorDataAction->execute(
            $device,
            (float) $request->validated()['temperature'],
            Carbon::parse($request->validated()['recorded_at']) ?? null
        );

        return response()->json([
            'data' => new SensorDataResource($sensorData),
            'message' => 'Sensor data recorded successfully',
        ], 201);
    }

    /**
     * Display sensor data for a specific device.
     */
    public function show(int $deviceId, Request $request): JsonResponse
    {
        $user = $request->user();
        $device = $this->deviceRepository->findByUser($user, $deviceId);

        if (!$device) {
            return response()->json(['message' => 'Device not found'], 404);
        }

        $sensorData = $this->sensorDataRepository->paginateByDevice($device, 15);

        return response()->json([
            'data' => SensorDataResource::collection($sensorData->items()),
            'meta' => [
                'current_page' => $sensorData->currentPage(),
                'last_page' => $sensorData->lastPage(),
                'per_page' => $sensorData->perPage(),
                'total' => $sensorData->total(),
            ],
        ]);
    }

    /**
     * Display sensor data statistics for a specific device.
     */
    public function statistics(int $deviceId, Request $request): JsonResponse
    {
        $user = $request->user();
        $device = $this->deviceRepository->findByUser($user, $deviceId);

        if (!$device) {
            return response()->json(['message' => 'Device not found'], 404);
        }

        $statistics = $this->sensorDataRepository->getStatistics($device);

        return response()->json($statistics);
    }
}
