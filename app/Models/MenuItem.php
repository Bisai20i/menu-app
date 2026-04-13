<?php

namespace App\Models;

use App\Traits\BelongsToRestaurant;
use App\Traits\HasDynamicTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuItem extends Model
{
    use HasDynamicTable;
    use BelongsToRestaurant;

    protected $fillable = [
        'menu_category_id', 'name', 'description', 
        'price', 'image', 'is_available', 'is_featured', 'dietary_info', 'restaurant_id'
    ];

    public function getTableColumns(): array
    {
        return [
            'image'        => ['label' => 'Image', 'sortable' => false, 'type' => 'image'],
            'name'         => ['label' => 'Name', 'sortable' => true, 'type' => 'text'],
            'menu_category_id' => ['label' => 'Category', 'sortable' => true, 'type' => 'relation', 'relation' => 'category', 'field' => 'name'],
            'price'        => ['label' => 'Price', 'sortable' => true, 'type' => 'text'],
            'is_available' => ['label' => 'Available', 'sortable' => true, 'type' => 'toggleable'],
            'is_featured'  => ['label' => 'Featured', 'sortable' => true, 'type' => 'toggleable'],
        ];
    }

    protected $appends = [ 'image_url'];

    public function getTableRelations(): array
    {
        return ['category'];
    }

    // Cast JSON to array automatically
    protected $casts = [
        'dietary_info' => 'array',
        'is_available' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class, 'menu_category_id');
    }

    /**
     * get image url attribute
     */
    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/'.$this->image) : null;
    }
}
