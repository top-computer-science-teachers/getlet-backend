<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
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
            'order_type' => 'nullable|string|in:send,take',
            'object' => 'nullable|string',
            'date' => 'nullable|string',
            'price_type' => 'nullable|string|in:fix,contract',
            'price' => 'required_if:price_type,fix|integer|nullable',
            'sender_contact' => 'nullable|string',
            'receiver_contact' => 'nullable|string',
            'from_city_id' => 'nullable|string|exists:cities,id',
            'to_city_id' => 'nullable|string|exists:cities,id',
        ];
    }
}
