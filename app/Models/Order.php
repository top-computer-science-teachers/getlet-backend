<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasUuids;

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'type',
        'status',
        'date',
        'price',
        'price_type',
        'sender_contact',
        'receiver_contact',
        'from_city_id',
        'to_city_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function packages(): HasMany
    {
        return $this->hasMany(OrderPackage::class, 'order_id', 'id');
    }

    public function process(): HasOne
    {
        return $this->hasOne(OrderProcess::class, 'order_id', 'id');
    }

    public function to_city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'to_city_id', 'id');
    }

    public function from_city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'from_city_id', 'id');
    }

}
