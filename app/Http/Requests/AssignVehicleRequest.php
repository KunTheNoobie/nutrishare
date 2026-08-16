<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Author: Yap Zhing Shuen
 * Module 3: Claims & Logistics Distribution
 */
class AssignVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'plate_number' => 'required|string|max:20',
            'vehicle_type' => 'required|in:van,truck,car,motorcycle',
            'driver_name' => 'required|string|max:255',
            'driver_phone' => ['required', 'string', 'min:9', 'max:20', 'regex:/^(\+?[\d\s\-\(\)]){9,20}$/'],
            'capacity_kg' => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'driver_phone.min' => 'Please enter a valid driver phone number (at least 9 digits, e.g. 012-3456789 or +60123456789).',
            'driver_phone.regex' => 'Please enter a valid driver phone number (at least 9 digits, e.g. 012-3456789 or +60123456789).',
        ];
    }
}
