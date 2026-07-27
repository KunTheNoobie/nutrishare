<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * SECURITY (Module 2): Weak Password Prevention.
 * Enforces minimum password complexity requirements.
 */
class RegisterUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',           // Minimum 8 characters
                'confirmed',       // Must match password_confirmation
                'regex:/[A-Z]/',   // At least one uppercase letter
                'regex:/[a-z]/',   // At least one lowercase letter
                'regex:/[0-9]/',   // At least one digit
                'regex:/[@$!%*?&#]/', // At least one special character
            ],
            'role' => 'required|in:donor,ngo',
            'organization_name' => 'required_if:role,ngo|nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'notification_preference' => 'nullable|in:email,sms,both',
        ];
    }

    public function messages(): array
    {
        return [
            'password.min' => 'Password must be at least 8 characters.',
            'password.regex' => 'Password must contain uppercase, lowercase, number, and special character.',
            'organization_name.required_if' => 'Organization name is required for NGO registration.',
        ];
    }
}
