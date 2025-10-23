<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\SensorData;

use App\Domain\Device\Models\Device;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request for recording sensor data.
 *
 * This request handles validation and authorization for sensor data recording.
 */
class CreateSensorDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Device can record data
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'device_serial' => ['required', 'string', 'exists:devices,serial_number'],
            'temperature' => ['required', 'numeric', 'between:-50,100'],
            'recorded_at' => ['sometimes', 'date', 'before_or_equal:now'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'device_serial.required' => 'The device serial number is required.',
            'device_serial.exists' => 'The specified device does not exist.',
            'temperature.required' => 'The temperature field is required.',
            'temperature.numeric' => 'The temperature must be a number.',
            'temperature.between' => 'The temperature must be between -50 and 100 degrees Celsius.',
        ];
    }
}
