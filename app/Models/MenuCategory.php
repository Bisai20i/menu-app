<?php
namespace App\Models;

use App\Traits\BelongsToRestaurant;
use App\Traits\HasDynamicTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class MenuCategory extends Model
{
    use HasDynamicTable;
    use BelongsToRestaurant;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $slug = Str::slug($category->name);
            $count = self::withoutGlobalScopes()->where('slug', 'LIKE', "$slug%")->count();
            $category->slug = $count ? "$slug-$count" : $slug;
        });

        static::updating(function ($category) {
            if ($category->isDirty('name')) {
                $slug = Str::slug($category->name);
                $count = self::withoutGlobalScopes()->where('slug', 'LIKE', "$slug%")->count();
                $category->slug = $count ? "$slug-$count" : $slug;
            }
        });
    }

    protected $fillable = ['name', 'slug', 'description', 'image', 'sort_order', 'is_active', 'restaurant_id'];

    public function getTableColumns(): array
    {
        return [
            'image' => ['label' => 'Image', 'sortable' => false, 'type' => 'image'],
            'name' => ['label' => 'Name', 'sortable' => true, 'type' => 'text'],
            'slug' => ['label' => 'Slug', 'sortable' => true, 'type' => 'text'],
            'sort_order' => ['label' => 'Order', 'sortable' => true, 'type' => 'text'],
            'is_active' => ['label' => 'Status', 'sortable' => true, 'type' => 'toggleable'],
            'created_at' => ['label' => 'Created', 'sortable' => true, 'type' => 'date'],
        ];
    }

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $appends = ['image_url'];

    /**
     * get image url attribute
     */
    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    public function menuItems(): HasMany
    {
        return $this->hasMany(MenuItem::class)->orderBy('name');
    }
}
