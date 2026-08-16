<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Author: Cheon Jie Han / Liew Yi Ler
 * Module 1 & 2: Platform Analytics & Report Generation
 */
class StoreReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && ($this->user()->isAdmin() || $this->user()->isModerator());
    }

    public function rules(): array
    {
        return [
            'type' => 'required|in:sdg_impact,donation_summary,user_activity',
            'title' => 'required|string|max:255',
        ];
    }
}
