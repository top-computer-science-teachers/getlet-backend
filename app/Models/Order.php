<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'type',
        'object',
        'date',
        'price',
        'sender_contact',
        'receiver_contact',
        'from_country_id',
        'from_city_id',
        'to_country_id',
        'to_city_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function to_country()
    {
        return $this->belongsTo(Country::class, 'to_country_id', 'id');
    }

    public function to_city()
    {
        return $this->belongsTo(City::class, 'to_city_id', 'id');
    }

    public function from_country()
    {
        return $this->belongsTo(Country::class, 'to_city_id', 'id');
    }

    public function from_city()
    {
        return $this->belongsTo(City::class, 'from_city_id', 'id');
    }

}
