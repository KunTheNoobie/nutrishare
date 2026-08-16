<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Author: Cheon Jie Han
 * Module 2: NGO Verification & User Profile Management
 */
class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $user = $this->user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['required', 'string', 'min:9', 'max:20', 'regex:/^(\+?[\d\s\-\(\)]){9,20}$/'],
            'notification_preference' => ['required', Rule::in(['email', 'sms', 'both'])],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'], // 5MB max
        ];

        if ($user->isNgo()) {
            $rules['organization_name'] = ['required', 'string', 'max:255'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'phone.min' => 'Please enter a valid phone number (at least 9 digits, e.g. 012-3456789 or +60123456789).',
            'phone.regex' => 'Please enter a valid phone number (at least 9 digits, e.g. 012-3456789 or +60123456789).',
        ];
    }
}
