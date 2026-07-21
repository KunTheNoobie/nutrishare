<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * SECURITY (Module 1): SQLi Prevention — Parameterized query validation.
 * All donation inputs are validated and sanitized before reaching Eloquent.
 */
class StoreDonationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && ($this->user()->isDonor() || $this->user()->isAdmin());
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'quantity' => 'required|numeric|min:0.01|max:99999',
            'unit' => 'required|in:kg,litres,items,boxes',
            'pickup_address' => 'required|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'expiry_date' => 'required|date|after:today',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'expiry_date.after' => 'The expiry date must be in the future.',
            'quantity.min' => 'Quantity must be at least 0.01.',
        ];
    }
}
