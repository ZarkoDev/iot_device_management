<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * API Resource for Alert model.
 *
 * This resource transforms Alert models into consistent API responses.
 */
class AlertResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'device_id' => $this->device_id,
            'sensor_data_id' => $this->sensor_data_id,
            'alert_type' => $this->alert_type,
            'is_temperature_alert' => $this->isTemperatureAlert(),
            'severity' => $this->severity,
            'message' => $this->message,
            'temperature' => $this->temperature,
            'threshold_min' => $this->threshold_min,
            'threshold_max' => $this->threshold_max,
            'condition_name' => $this->condition_name,
            'condition_description' => $this->condition_description,
            'timeout_minutes' => $this->timeout_minutes,
            'resolved_at' => optional($this->resolved_at)->toDateTimeString(),
            'priority' => $this->getPriority(),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
