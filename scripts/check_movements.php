<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

$movements = \App\Models\StockMovement::with('product', 'user')->limit(10)->get();
echo "Total movements: " . \App\Models\StockMovement::count() . "\n";
echo "\nRecent movements:\n";
foreach ($movements as $m) {
    echo "ID: {$m->id}, Product: {$m->product?->name}, Type: {$m->type}, Qty: {$m->quantity}, User: {$m->user?->name}, Created: {$m->created_at}\n";
}
