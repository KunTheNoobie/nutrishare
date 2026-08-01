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
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:102400',
            'image_url' => 'nullable|url|max:1000',
            'image_urls' => 'nullable|array|max:5',
            'image_urls.*' => 'nullable|url|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'expiry_date.after' => 'The expiry date must be a time in the future.',
            'quantity.min' => 'Quantity must be at least 0.01.',
            'images.max' => 'You cannot upload more than 5 images.',
            'images.*.uploaded' => 'An image could not be uploaded. It might exceed the server limits (try an image under 100MB).',
            'images.*.max' => 'An image size must not exceed 100MB.',
            'images.*.image' => 'All uploaded files must be valid images (JPEG, PNG, GIF).',
        ];
    }
}
