<?php

namespace App\Models;

use App\Traits\BelongsToRestaurant;
use App\Traits\HasDynamicTable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RestaurantTable extends Model
{
    use HasDynamicTable;
    use BelongsToRestaurant;
    protected $fillable = [
        'uuid',
        'table_number',
        'capacity',
        'section',
        'is_active',
        'status',
        'restaurant_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'capacity' => 'integer',
    ];

    protected static function booted()
    {
        parent::boot();
        static::creating(function ($table) {
            $uuid = (string) Str::uuid();
            while (self::where('uuid', $uuid)->exists()) {
                $uuid = (string) Str::uuid();
            }
            $table->uuid = $uuid;
        });
    }

    public function getTableColumns(): array
    {
        return [
            'table_number' => ['label' => 'Table #', 'sortable' => true, 'type' => 'text'],
            'capacity'     => ['label' => 'Capacity', 'sortable' => true, 'type' => 'text'],
            'section'      => ['label' => 'Section', 'sortable' => true, 'type' => 'text'],
            'status'       => ['label' => 'Status', 'sortable' => true, 'type' => 'badge'],
            'is_active'    => ['label' => 'Active', 'sortable' => true, 'type' => 'toggleable'],
            'created_at'   => ['label' => 'Added', 'sortable' => true, 'type' => 'date'],
        ];
    }

    public function getTableBadges(): array
    {
        return [
            'status' => [
                'available' => 'bg-success',
                'occupied'  => 'bg-danger',
                'reserved'  => 'bg-warning',
            ],
        ];
    }

    /**
     * All sessions ever created for this table.
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(TableSession::class);
    }

    /**
     * The first active session for this table, if any.
     * (Replicated for backward compatibility or direct access)
     */
    public function activeSession(): HasOne
    {
        return $this->hasOne(TableSession::class)->where('status', 'active')->latestOfMany();
    }

    /**
     * All currently active sessions for this table.
     */
    public function activeSessions(): HasMany
    {
        return $this->hasMany(TableSession::class)->where('status', 'active');
    }

    /**
     * The latest active session for this table, if any.
     */
    public function latestActiveSession(): HasOne
    {
        return $this->hasOne(TableSession::class)->where('status', 'active')->latest();
    }

    /**
     * All orders ever placed at this table across all sessions.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function hasActiveSession(): bool
    {
        return $this->activeSessions()->exists();
    }

    /**
     * Open a new session for this table.
     * Allows multiple concurrent sessions.
     */
    public function openSession(int $restaurantId, ?int $openedByAdminId = null, ?int $guestCount = null, ?string $deviceId = null): TableSession
    {
        $session = $this->sessions()->create([
            'restaurant_id' => $restaurantId,
            'opened_by'     => $openedByAdminId,
            'guest_count'   => $guestCount,
            'device_id'     => $deviceId,
            'status'        => 'active',
        ]);

        $this->update(['status' => 'occupied']);

        return $session;
    }

    /**
     * Close a session and update table status if no active sessions remain.
     */
    public function closeSession(TableSession $session, ?int $closedByAdminId = null): void
    {
        $session->update([
            'status' => 'paid',
            'closed_by' => $closedByAdminId,
            'closed_at' => now(),
        ]);

        if (!$this->hasActiveSession()) {
            $this->update(['status' => 'available']);
        }
    }
}
