<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFoodItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && ($this->user()->isDonor() || $this->user()->isAdmin());
    }

    public function rules(): array
    {
        return [
            'donation_id' => 'required|exists:donations,id',
            'inventory_location_id' => 'nullable|exists:inventory_locations,id',
            'category_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'quantity' => 'required|numeric|min:0.01',
            'unit' => 'required|in:kg,litres,items',
            'expiry_date' => 'required|date|after:today',
            'storage_requirements' => 'required|in:cold,dry,frozen,ambient',
            'is_perishable' => 'required|boolean',
            'allergen_tags' => 'nullable|array',
            'allergen_tags.*' => 'exists:allergen_tags,id',
        ];
    }
}
