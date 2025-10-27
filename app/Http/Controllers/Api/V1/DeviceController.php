<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Domain\Device\Actions\CreateDeviceAction;
use App\Domain\Device\Actions\DetachDeviceAction;
use App\Domain\Device\Actions\AttachDeviceAction;
use App\Domain\Device\Contracts\DeviceRepositoryInterface;
use App\Domain\User\Contracts\UserRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Device\AttachDeviceRequest;
use App\Http\Requests\Api\V1\Device\CreateDeviceRequest;
use App\Http\Requests\Api\V1\Device\DetachDeviceRequest;
use App\Http\Resources\Api\V1\DeviceResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * API Controller for Device management.
 *
 * This controller handles device-related API endpoints following RESTful conventions.
 * All business logic is delegated to Actions and Services.
 */
class DeviceController extends Controller
{
    public function __construct(
        private readonly DeviceRepositoryInterface $deviceRepository,
        private readonly UserRepositoryInterface   $userRepository,
        private readonly CreateDeviceAction        $createDeviceAction,
        private readonly AttachDeviceAction        $attachDeviceAction,
        private readonly DetachDeviceAction        $detachDeviceAction
    ) {}

    /**
     * Display a listing of devices for the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        $devices = $this->deviceRepository->paginate();

        return response()->json([
            'data' => DeviceResource::collection($devices->items()),
            'meta' => [
                'current_page' => $devices->currentPage(),
                'last_page' => $devices->lastPage(),
                'per_page' => $devices->perPage(),
                'total' => $devices->total(),
            ],
        ]);
    }

    /**
     * Store a newly created device.
     */
    public function store(CreateDeviceRequest $request): JsonResponse
    {
        $device = $this->createDeviceAction->execute($request->validated());

        return response()->json([
            'data' => new DeviceResource($device),
            'message' => 'Device created successfully',
        ], 201);
    }

    /**
     * Display the specified device.
     */
    public function show(int $id, Request $request): JsonResponse
    {
        $user = $request->user();
        $device = $this->deviceRepository->findByUser($user, $id);

        if (!$device) {
            return response()->json(['message' => 'Device not found'], 404);
        }

        return response()->json([
            'data' => new DeviceResource($device),
        ]);
    }

    /**
     * Attach device to user.
     */
    public function attach(int $id, AttachDeviceRequest $request): JsonResponse
    {
        $device = $this->deviceRepository->findById($id);

        if (!$device) {
            return response()->json(['message' => 'Device not found'], 404);
        }

        $newOwner = $this->userRepository->findById((int) $request->input('user_id'));
        $device = $this->attachDeviceAction->execute($device, $newOwner);

        return response()->json([
            'data' => new DeviceResource($device),
            'message' => 'Device ownership attached successfully',
        ]);
    }

    /**
     * Detach device from any users.
     */
    public function detach(int $id, DetachDeviceRequest $request): JsonResponse
    {
        $device = $this->deviceRepository->findById($id);

        if (!$device) {
            return response()->json(['message' => 'Device not found'], 404);
        }

        $device = $this->detachDeviceAction->execute($device);

        return response()->json([
            'data' => new DeviceResource($device),
            'message' => 'Device ownership detached successfully',
        ]);
    }

    /**
     * Remove the specified device.
     */
    public function destroy(int $id, Request $request): JsonResponse
    {
        $user = $request->user();
        $device = $this->deviceRepository->findById($id);

        if (!$device || $device->user_id !== $user->id) {
            return response()->json(['message' => 'Device not found'], 404);
        }

        $this->deviceRepository->delete($device);

        return response()->json(['message' => 'Device deleted successfully']);
    }
}
