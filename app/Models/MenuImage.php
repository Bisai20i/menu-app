<?php

namespace App\Models;


use App\Traits\BelongsToRestaurant;
use Illuminate\Database\Eloquent\Model;

class MenuImage extends Model
{
    use BelongsToRestaurant;
    protected $fillable = [
        'media_path',
        'media_type',
        'media_source',
        'is_active',
        'sort_order',
        'restaurant_id',
    ];


    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getDisplayUrlAttribute()
    {
        if ($this->media_source === 'external') {
            return $this->media_path;
        }

        return asset('storage/' . $this->media_path);
    }
}
