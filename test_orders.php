<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$orders = App\Models\Order::all();
echo "Total Orders: " . $orders->count() . "\n";
foreach($orders as $o) {
    echo "ID: " . $o->order_id . " | Status: " . $o->status . "\n";
}
