<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyRestaurantStat extends Model
{
    protected $fillable = [
        'restaurant_id',
        'date',
        'menu_views',
        'total_orders',
        'total_revenue',
    ];

    protected $casts = [
        'date' => 'date',
        'total_revenue' => 'decimal:2',
    ];

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }
}