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

    /**
     * Restaurant Wifi QR value stored inside the `settings` JSON column.
     */
    public function getRestaurantWifiQrAttribute(): ?string
    {
        $settings = is_array($this->settings) ? $this->settings : [];
        $wifiQr = $settings['restaurant_wifi_qr'] ?? null;

        if (is_string($wifiQr)) {
            $wifiQr = asset('storage/' . trim($wifiQr));
            return $wifiQr !== '' ? $wifiQr : null;
        }

        return null;
    }

    /**
     * Google Review Link stored inside the `settings` JSON column.
     */
    public function getGoogleReviewLinkAttribute(): ?string
    {
        $settings = is_array($this->settings) ? $this->settings : [];
        $link = $settings['google_review_link'] ?? null;

        if ($link && trim($link) !== '') {
            return $link;
        }

        $placeId = $settings['google_place_id'] ?? null;
        if ($placeId && trim($placeId) !== '') {
            return "https://search.google.com/local/writereview?placeid=" . trim($placeId);
        }

        return null;
    }

    /**
     * Google Place ID stored inside the `settings` JSON column.
     */
    public function getGooglePlaceIdAttribute(): ?string
    {
        $settings = is_array($this->settings) ? $this->settings : [];
        return $settings['google_place_id'] ?? null;
    }
}
