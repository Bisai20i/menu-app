<?php

namespace App\Livewire;

use App\Models\RestaurantTable;
use Livewire\Component;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Response;

class TableQRGenerator extends Component
{
    public $search = '';

    public function downloadQR($uuid, $tableNumber)
    {
        $url = url('/table/' . $uuid);
        
        $image = QrCode::size(500)
            ->margin(2)
            ->errorCorrection('H')
            ->generate($url);

        $filename = 'QR_' . str_replace(' ', '_', $tableNumber) . '.svg';

        return Response::streamDownload(function () use ($image) {
            echo $image;
        }, $filename, [
            'Content-Type' => 'image/svg+xml',
        ]);
    }

    public function render()
    {
        $tables = RestaurantTable::where('table_number', 'like', '%' . $this->search . '%')
            ->orWhere('section', 'like', '%' . $this->search . '%')
            ->orderBy('table_number')
            ->get();

        return view('livewire.table-qr-generator', [
            'tables' => $tables
        ]);
    }
}
