<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkersAvailabilityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'worker_id' => 'required|integer',
            'name' => 'nullable|string',
            'services' => 'nullable|array',
            'availability_type' => 'nullable|string',
            'weekly_availability' => 'nullable|array',
            'locations' => 'nullable|array',
            'coordinates' => 'nullable|array',
            'preferences' => 'nullable|string',
            'expected_rate' => 'nullable|array',
        ];
    }
}
