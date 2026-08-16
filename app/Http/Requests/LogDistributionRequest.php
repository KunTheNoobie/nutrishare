<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Author: Yap Zhing Shuen
 * Module 3: Claims & Logistics Distribution
 */
class LogDistributionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'beneficiaries_count' => 'required|integer|min:1',
            'distribution_location' => 'required|string|max:500',
            'quantity_distributed' => 'required|numeric|min:0.01',
            'unit' => 'required|in:kg,litres,items,boxes',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}
