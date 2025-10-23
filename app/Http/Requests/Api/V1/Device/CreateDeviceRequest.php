<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Device;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request for creating a new device.
 *
 * This request handles validation and authorization for device creation.
 */
class CreateDeviceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authenticated users can create devices
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'serial_number' => ['required', 'string', 'max:255', 'unique:devices'],
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'serial_number.required' => 'The serial number field is required.',
            'serial_number.unique' => 'The serial number has already been taken.',
            'name.required' => 'The name field is required.',
        ];
    }
}
