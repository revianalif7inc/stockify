<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Test the new query
$lowStockProducts = App\Models\Product::whereColumn('current_stock', '<=', 'min_stock')->count();
echo "Count of critical stock products: " . $lowStockProducts . "\n\n";

$lowStockProductsList = App\Models\Product::whereColumn('current_stock', '<=', 'min_stock')
    ->orderBy('current_stock')
    ->limit(5)
    ->get();

echo "Critical Stock Products:\n";
echo str_repeat("-", 80) . "\n";
foreach ($lowStockProductsList as $p) {
    echo $p->id . " | " . $p->name . " | Stock: " . $p->current_stock . " / Min: " . $p->min_stock . "\n";
}
?>