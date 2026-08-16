<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Author: Yap Zhing Shuen
 * Module 3: Claims & Logistics Distribution
 */
class CreateReceiptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'quantity_collected' => 'required|numeric|min:0.01',
            'unit' => 'required|in:kg,litres,items,boxes',
            'collected_by' => 'required|string|max:255',
            'condition_notes' => 'nullable|string|max:500',
        ];
    }
}
