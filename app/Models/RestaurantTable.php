<?php

namespace App\Models;

use App\Traits\BelongsToRestaurant;
use Illuminate\Database\Eloquent\Model;

use App\Traits\HasDynamicTable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

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
}
