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
            'expiry_date' => 'required|date|after:now',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:102400',
            'image_url' => 'nullable|url|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'expiry_date.after' => 'The expiry date must be a time in the future.',
            'quantity.min' => 'Quantity must be at least 0.01.',
            'image.uploaded' => 'The image could not be uploaded. It might exceed the server limits (try an image under 100MB).',
            'image.max' => 'The image size must not exceed 100MB.',
            'image.image' => 'The uploaded file must be a valid image (JPEG, PNG, GIF).',
        ];
    }
}
