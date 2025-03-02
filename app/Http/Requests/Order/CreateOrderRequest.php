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
            'type' => 'required|string',
            'object' => 'required|string',
            'date' => 'required|string',
            'price' => 'required|string',
            'sender_contact' => 'required|string',
            'receiver_contact' => 'required|string',
            'from_country_id' => 'required|string',
            'from_city_id' => 'required|string',
            'to_country_id' => 'required|string',
            'to_city_id' => 'required|string',
        ];
    }
}
