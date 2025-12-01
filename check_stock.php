<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$products = App\Models\Product::all();
echo "ID | Name | Current Stock | Min Stock | Critical?\n";
echo str_repeat("-", 80) . "\n";
foreach ($products as $p) {
    $critical = $p->current_stock <= $p->min_stock ? 'YES' : 'NO';
    echo $p->id . " | " . $p->name . " | " . $p->current_stock . " | " . $p->min_stock . " | " . $critical . "\n";
}
?>