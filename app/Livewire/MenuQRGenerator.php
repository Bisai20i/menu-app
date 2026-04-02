<?php
namespace App\Livewire;

use App\Models\Restaurant;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Livewire\Component;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MenuQRGenerator extends Component
{
    public ?int $restaurantId = null;

    public function mount()
    {
        $this->restaurantId = auth()->guard('admin')->user()?->restaurant_id;
    }

    public function downloadQR()
    {
        $restaurant = $this->getRestaurant();

        if (! $restaurant) {
            return;
        }

        $menuUrl  = url('/' . $restaurant->slug);
        $filename = 'Menu_QR_' . Str::slug($restaurant->name) . '.svg';

        $qrPng = (string) QrCode::format('svg')
            ->size(500)
            ->margin(2)
            ->errorCorrection('H')
            ->generate($menuUrl);

        return response()->streamDownload(
            fn() => print($qrPng),
            $filename,
            ['Content-Type' => 'image/svg+xml']
        );
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Private helpers
    // ─────────────────────────────────────────────────────────────────────────

    private function getRestaurant(): ?Restaurant
    {
        if (! $this->restaurantId) {
            return null;
        }

        return Restaurant::query()
            ->select([
                'id',
                'slug',
                'name',
                'description',
                'logo_path',
                'email',
                'phone',
                'address',
                'settings',
            ])
            ->find($this->restaurantId);
    }


    public function render()
    {
        $restaurant = $this->getRestaurant();
        $menuUrl    = $restaurant ? url('/' . $restaurant->slug) : '#';
        $menuQrSvg  = $restaurant
            ? QrCode::format('svg')->size(200)->errorCorrection('H')->generate($menuUrl)
            : null;

        return view('livewire.menu-qr-generator', [
            'restaurant' => $restaurant,
            'menuUrl'    => $menuUrl,
            'menuQrSvg'  => $menuQrSvg,
        ]);
    }
}
