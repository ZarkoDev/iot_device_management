<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * API Resource for SensorData model.
 *
 * This resource transforms SensorData models into consistent API responses.
 */
class SensorDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'temperature' => $this->temperature,
            'formatted_temperature' => $this->formatted_temperature,
            'recorded_at' => optional($this->recorded_at)->toDateTimeString(),
            'is_within_normal_range' => $this->isWithinNormalRange(),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'device_id' => $this->device ? new DeviceResource($this->device) : null,
        ];
    }
}
