<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserStatistics extends Model
{
    use HasUuids;

    protected $table = 'user_statistics';

    protected $fillable = [
        'user_id',
        'order_take_created_count',
        'order_take_count',
        'order_take_completed_count',
        'order_take_failed_count',
        'order_send_created_count',
        'order_send_count',
        'order_send_completed_count',
        'order_send_failed_count',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
