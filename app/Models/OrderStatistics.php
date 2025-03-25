<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class OrderStatistics extends Model
{
    use HasUuids;

    protected $table = 'order_statistics';

    protected $fillable = [
        'completed_orders_count',
        'cancelled_orders_count'
    ];

}
