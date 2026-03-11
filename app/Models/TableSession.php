<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Str;

class TableSession extends Model
{
    protected $fillable = [
        'uuid',
        'restaurant_table_id',
        'restaurant_id',
        'opened_by',
        'closed_by',
        'status',
        'guest_count',
        'notes',
        'opened_at',
        'closed_at',
    ];

    protected $casts = [
        'opened_at'   => 'datetime',
        'closed_at'   => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (TableSession $session) {
            $session->uuid       = (string) Str::uuid();
            $session->opened_at  = now();
        });
    }

    // ── Relationships ──────────────────────────────────────────────

    public function table(): BelongsTo
    {
        return $this->belongsTo(RestaurantTable::class, 'restaurant_table_id');
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function openedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'opened_by');
    }

    public function closedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'closed_by');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * All order items across every order in this session.
     * Useful for generating the full session bill.
     */
    public function orderItems(): HasManyThrough
    {
        return $this->hasManyThrough(OrderItem::class, Order::class);
    }

    // ── Scopes ─────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeForRestaurant($query, int $restaurantId)
    {
        return $query->where('restaurant_id', $restaurantId);
    }

    // ── Helpers ────────────────────────────────────────────────────

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Grand total of all orders in the session.
     */
    public function grandTotal(): float
    {
        return (float) $this->orders()->sum('total_amount');
    }

    /**
     * Total amount already paid across orders in this session.
     */
    public function totalPaid(): float
    {
        return (float) $this->orders()->where('is_paid', true)->sum('total_amount');
    }

    /**
     * Close the session and update the table status back to available.
     */
    public function close(int $closedByAdminId = null, string $status = 'paid'): void
    {
        $this->update([
            'status'    => $status,
            'closed_by' => $closedByAdminId,
            'closed_at' => now(),
        ]);

        $this->table()->update(['status' => 'available']);
    }
}