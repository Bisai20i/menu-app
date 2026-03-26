<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

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
                $restaurant->slug = Str::slug($restaurant->name);

                // Ensure slug is unique
                $originalSlug = $restaurant->slug;
                $count        = 2;
                while (static::where('slug', $restaurant->slug)->exists()) {
                    $restaurant->slug = $originalSlug . '-' . $count++;
                }
            }
        });

        static::updating(function ($restaurant) {
            if ($restaurant->isDirty('name') && ! $restaurant->isDirty('slug')) {
                $restaurant->slug = Str::slug($restaurant->name);

                // Ensure slug is unique
                $originalSlug = $restaurant->slug;
                $count        = 2;
                while (static::where('slug', $restaurant->slug)->where('id', '!=', $restaurant->id)->exists()) {
                    $restaurant->slug = $originalSlug . '-' . $count++;
                }
            }
        });
    }

    /**
     * All daily stats for this restaurant.
     */
    public function dailyStats()
    {
        return $this->hasMany(DailyRestaurantStat::class);
    }

    /**
     * Helper to get today's specific stat row.
     */
    public function todayStat()
    {
        return $this->hasOne(DailyRestaurantStat::class)
            ->where('date', now()->toDateString());
    }

    /**
     * Payment QR value stored inside the `settings` JSON column.
     *
     * Expected to be either:
     * - a `data:image/...` URL (base64), or
     * - an absolute URL, or
     * - a site-relative path.
     */
    public function getPaymentQrAttribute(): ?string
    {
        $settings = is_array($this->settings) ? $this->settings : [];
        $paymentQr = $settings['payment_qr'] ?? null;

        if (is_string($paymentQr)) {
            $paymentQr = trim($paymentQr);
            return $paymentQr !== '' ? $paymentQr : null;
        }

        return null;
    }
}
