<?php

namespace App\Http\Resources\Order;

use App\Http\Resources\City\CityResource;
use App\Http\Resources\City\CountryResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_type' => $this->order_type,
            'order_status' => $this->order_status,
            'object' => $this->object,
            'date' => Carbon::parse($this->date)->format('Y-m-d'),
            'price_type' => $this->price_type,
            'price' => $this->price_type == 'contract' ? 'Договорная' : $this->price,
            'sender_contact' => $this->sender_contact,
            'receiver_contact' => $this->receiver_contact,
            'from' => [
                'country' => CountryResource::make($this->from_city->country),
                'city' => CityResource::make($this->from_city),
            ],
            'to' => [
                'country' => CountryResource::make($this->to_city),
                'city' => CityResource::make($this->to_city),
            ],
            'user' => UserResource::make($this->user)
        ];
    }
}
