<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaiterCall extends Model
{
    protected $fillable = [
        'restaurant_id',
        'restaurant_table_id',
        'table_session_id',
        'status',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function table()
    {
        return $this->belongsTo(RestaurantTable::class, 'restaurant_table_id');
    }

    public function session()
    {
        return $this->belongsTo(TableSession::class, 'table_session_id');
    }
}
