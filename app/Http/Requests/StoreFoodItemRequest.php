<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreFoodItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'donation_id' => 'nullable|exists:donations,id',
            'inventory_location_id' => 'nullable|exists:inventory_locations,id',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'quantity' => 'required|numeric|min:0.01',
            'unit' => 'required|in:kg,litres,items',
            'expiry_date' => 'required|date|after:today',
            'storage_requirements' => 'required|in:cold,dry,frozen,ambient',
            'is_perishable' => 'required|boolean',
            'images' => 'nullable|array|max:3',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'image_url' => 'nullable|url|max:1000',
            'allergen_tags' => 'nullable|array',
            'allergen_tags.*' => 'exists:allergen_tags,id',
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $locationId = $this->input('inventory_location_id');
            $quantity = (float) $this->input('quantity');

            if ($locationId && $quantity > 0) {
                $location = \App\Models\InventoryLocation::find($locationId);
                if ($location && $location->capacity !== null) {
                    $remaining = $location->availableCapacity();
                    if ($quantity > $remaining) {
                        $validator->errors()->add('quantity', "Quantity exceeds available capacity. Only {$remaining} remaining in this location.");
                    }
                }
            }
        });
    }
}
