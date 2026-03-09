<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Restaurant extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'description',
        'logo_path',
        'email',
        'phone',
        'address',
        'is_active',
        'settings',
    ];

    protected $casts = [
        'settings'  => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Relationship to the Admin
     */
    public function admin(): HasOne
    {
        return $this->hasOne(Admin::class);
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($restaurant) {
            if (empty($restaurant->slug)) {
                $restaurant->slug = \Illuminate\Support\Str::slug($restaurant->name);
                
                // Ensure slug is unique
                $originalSlug = $restaurant->slug;
                $count = 2;
                while (static::where('slug', $restaurant->slug)->exists()) {
                    $restaurant->slug = $originalSlug . '-' . $count++;
                }
            }
        });
    }
}
