<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Author: Cheon Jie Han
 * Module 2: NGO Verification & Document Review
 */
class ReviewVerificationDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && ($this->user()->isAdmin() || $this->user()->isModerator());
    }

    public function rules(): array
    {
        return [
            'action' => 'required|in:approved,rejected',
            'admin_remarks' => 'nullable|string|max:500',
        ];
    }
}
