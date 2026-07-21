<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClaimRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isNgo() && $this->user()->isVerified();
    }

    public function rules(): array
    {
        return [
            'donation_id' => 'required|exists:donations,id',
            'justification' => 'required|string|max:1000',
            'pickup_scheduled_at' => 'required|date|after:now',
        ];
    }
}
