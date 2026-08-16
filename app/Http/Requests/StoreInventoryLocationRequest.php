<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Author: Wong Men Jing
 * Module 4: Inventory & Food Safety Compliance
 */
class StoreInventoryLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && ($this->user()->isNgo() || $this->user()->isAdmin() || $this->user()->isModerator());
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'storage_type' => 'required|in:cold,dry,frozen,ambient',
            'capacity' => 'required|numeric|min:0.01',
        ];
    }
}
