<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => 'required|string|in:send,take',
            'date' => 'required|string',
            'price_type' => 'required|string|in:fix,contract',
            'price' => 'required_if:price_type,fix|integer|nullable',
            'sender_contact' => 'nullable|string',
            'receiver_contact' => 'nullable|string',
            'from_city_id' => 'required|string|exists:cities,id',
            'to_city_id' => 'required|string|exists:cities,id',
            'packages' => 'required|array',
            'packages.*.title' => 'required|string|max:255',
            'packages.*.description' => 'nullable|string',
            'packages.*.weight' => 'nullable|integer',
        ];
    }
}
