<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'uuid',
        'table_session_id',
        'restaurant_table_id',
        'restaurant_id',
        'status',
        'is_paid',
        'paid_at',
        'total_amount',
        'note',
        'confirmed_by',
        'served_by',
        'needs_user_confirmation',
    ];

    protected $casts = [
        'is_paid'                 => 'boolean',
        'needs_user_confirmation' => 'boolean',
        'paid_at'                 => 'datetime',
        'total_amount'            => 'decimal:2',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (Order $order) {
            $order->uuid = (string) Str::uuid();
        });
    }

    // ── Relationships ──────────────────────────────────────────────

    public function session(): BelongsTo
    {
        return $this->belongsTo(TableSession::class, 'table_session_id');
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(RestaurantTable::class, 'restaurant_table_id');
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function confirmedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'confirmed_by');
    }

    public function servedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'served_by');
    }

    // ── Scopes ─────────────────────────────────────────────────────

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeUnpaid($query)
    {
        return $query->where('is_paid', false);
    }

    public function scopeForRestaurant($query, int $restaurantId)
    {
        return $query->where('restaurant_id', $restaurantId);
    }

    // ── Helpers ────────────────────────────────────────────────────

    public function isPending(): bool   { return $this->status === 'pending'; }
    public function isConfirmed(): bool { return $this->status === 'confirmed'; }
    public function isServed(): bool    { return $this->status === 'served'; }

    /**
     * Recalculate total_amount from order items and persist it.
     * Call this after adding/removing items.
     */
    public function recalculateTotal(): void
    {
        $this->update([
            'total_amount' => $this->items()->sum('subtotal'),
        ]);
    }

    /**
     * Mark this order as paid.
     */
    public function markAsPaid(): void
    {
        $this->update([
            'is_paid' => true,
            'paid_at' => now(),
        ]);
    }
}