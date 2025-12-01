<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Create a product with critical stock for testing
$product = App\Models\Product::create([
    'category_id' => 1,
    'supplier_id' => 1,
    'name' => 'Celana Jeans Premium',
    'description' => 'Celana jeans premium untuk pria',
    'purchase_price' => 50000,
    'selling_price' => 150000,
    'current_stock' => 2,
    'min_stock' => 5,
    'image' => 'default.jpg'
]);

echo "Product created successfully!\n";
echo "ID: " . $product->id . "\n";
echo "Name: " . $product->name . "\n";
echo "Current Stock: " . $product->current_stock . " (Min: " . $product->min_stock . ")\n";
echo "Status: CRITICAL\n";
?>