<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Author: Cheon Jie Han
 * Module 2: NGO Verification & Peer Trust Rating System
 */
class UploadVerificationDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isNgo();
    }

    public function rules(): array
    {
        return [
            'document_type' => 'required|in:registration_cert,tax_exemption,food_premise_license,other',
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png,webp|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'document.required' => 'Please select a document file to upload.',
            'document.mimes' => 'Only PDF, JPG, PNG, and WebP files are accepted for security verification.',
            'document.max' => 'The uploaded file size cannot exceed 5MB.',
        ];
    }
}
