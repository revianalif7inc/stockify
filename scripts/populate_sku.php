<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;

$products = Product::all();
$updated = 0;
foreach ($products as $product) {
    if (empty($product->sku)) {
        $product->sku = 'SKU' . str_pad($product->id, 4, '0', STR_PAD_LEFT);
        $product->save();
        $updated++;
        echo "Updated product ID {$product->id} -> {$product->sku}\n";
    }
}

echo "Done. Total updated: {$updated}\n";
?>