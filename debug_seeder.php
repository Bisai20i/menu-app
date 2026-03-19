<?php

use App\Models\SubscriptionPlan;
use App\Models\Admin;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $admin = Admin::first();
    if (!$admin) {
        die("No admin found\n");
    }
    $adminId = $admin->id;
    
    SubscriptionPlan::create([
        'admin_id' => $adminId,
        'name' => 'Starter',
        'price' => 0,
        'duration_unit' => 'month',
        'duration_value' => 1,
        'currency' => '$',
        'features' => ['PDF / image menu', 'One QR code', 'Basic analytics'],
        'published' => true,
        'sort_order' => 1,
    ]);
    echo "Success\n";
} catch (\Exception $e) {
    echo $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
