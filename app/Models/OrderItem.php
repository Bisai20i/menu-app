<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'menu_item_id',
        'restaurant_id',
        'quantity',
        'unit_price',
        'subtotal',
        'special_request',
        'is_cancelled',
        'cancellation_note',
    ];

    protected $casts = [
        'quantity'     => 'integer',
        'unit_price'   => 'decimal:2',
        'subtotal'     => 'decimal:2',
        'is_cancelled' => 'boolean',
    ];

    protected static function booted(): void
    {
        parent::boot();
        // Auto-compute subtotal before saving
        static::saving(function (OrderItem $item) {
            $item->subtotal = $item->unit_price * $item->quantity;
        });
    }

    // ── Relationships ──────────────────────────────────────────────

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }
}