<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    protected $fillable = [
        'country',
        'event_date',
        'order_id',
        'website',
        'publisher_name',
        'publisher_id',
        'sale_amount',
        'commission_amount',
        'user_id'
    ];

    protected $casts = [
        'event_date' => 'date',
        'sale_amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
