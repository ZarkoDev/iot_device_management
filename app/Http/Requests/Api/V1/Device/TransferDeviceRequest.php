<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Device;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request for transferring device ownership.
 *
 * This request handles validation and authorization for device transfer.
 */
class TransferDeviceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Device owner can transfer device
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'new_owner_id' => ['required', 'integer', 'exists:users,id'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'new_owner_id.required' => 'The new owner ID is required.',
            'new_owner_id.exists' => 'The selected new owner does not exist.',
        ];
    }
}
