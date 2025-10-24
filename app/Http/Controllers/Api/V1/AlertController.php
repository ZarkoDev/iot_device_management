<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Domain\Alerting\Contracts\AlertRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\AlertResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * API Controller for Alert management.
 *
 * This controller handles alert-related API endpoints following RESTful conventions.
 * All business logic is delegated to Actions and Services.
 */
class AlertController extends Controller
{
    public function __construct(
        private readonly AlertRepositoryInterface $alertRepository
    ) {}

    /**
     * Display a listing of alerts for the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $alerts = $this->alertRepository->paginateByUser($user, 15);

        return response()->json([
            'data' => AlertResource::collection($alerts->items()),
            'meta' => [
                'current_page' => $alerts->currentPage(),
                'last_page' => $alerts->lastPage(),
                'per_page' => $alerts->perPage(),
                'total' => $alerts->total(),
            ],
        ]);
    }

    /**
     * Display the specified alert.
     */
    public function show(int $id, Request $request): JsonResponse
    {
        $user = $request->user();
        $alert = $this->alertRepository->findByUser($user, $id);

        if (!$alert) {
            return response()->json(['message' => 'Alert not found'], 404);
        }

        return response()->json([
            'data' => new AlertResource($alert),
        ]);
    }

    /**
     * Resolve the specified alert.
     */
    public function resolve(int $id, Request $request): JsonResponse
    {
        $user = $request->user();
        $alert = $this->alertRepository->findById($id);

        if (!$alert || $alert->device->user_id !== $user->id) {
            return response()->json(['message' => 'Alert not found'], 404);
        }

        $alert = $this->alertRepository->resolve($alert);

        return response()->json([
            'data' => new AlertResource($alert),
            'message' => 'Alert resolved successfully',
        ]);
    }
}
