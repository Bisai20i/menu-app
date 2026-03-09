<?php

namespace App\Livewire;

use Livewire\Component;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Response;

class MenuQRGenerator extends Component
{
    public $restaurant;

    public function mount()
    {
        $this->restaurant = auth()->guard('admin')->user()->restaurant;
    }

    public function downloadQR()
    {
        if (!$this->restaurant) {
            return;
        }

        $url = url('/' . $this->restaurant->slug);
        
        $image = QrCode::size(500)
            ->margin(2)
            ->errorCorrection('H')
            ->merge(public_path('logo.png'), 0.3, true)
            ->generate($url);

        $filename = 'Menu_QR_' . str_replace(' ', '_', $this->restaurant->name) . '.png';

        return Response::streamDownload(function () use ($image) {
            echo $image;
        }, $filename, [
            'Content-Type' => 'image/png',
        ]);
    }

    public function render()
    {
        $menuUrl = $this->restaurant ? url('/' . $this->restaurant->slug) : '#';
        
        return view('livewire.menu-qr-generator', [
            'menuUrl' => $menuUrl
        ]);
    }
}
