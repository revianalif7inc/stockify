<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use App\Services\ProductService;
use Illuminate\Support\Str;
use Exception;

echo "Starting product attribute end-to-end test...\n";

try {
    $catName = 'TEST - Category For Attributes';
    $cat = Category::firstOrCreate(
        ['name' => $catName],
        ['name' => $catName, 'slug' => Str::slug($catName)]
    );

    echo "Using category id: {$cat->id}\n";

    $attr = ProductAttribute::firstOrCreate(
        ['category_id' => $cat->id, 'name' => 'Color'],
        ['type' => 'select', 'options' => ['Red', 'Blue', 'Green'], 'is_required' => 0, 'order' => 1]
    );

    echo "Using attribute id: {$attr->id} (type: {$attr->type})\n";

    $service = $app->make(ProductService::class);

    $data = [
        'name' => 'Test Product ' . time(),
        'category_id' => $cat->id,
        'purchase_price' => 100,
        'selling_price' => 150,
        'current_stock' => 5,
        'attributes' => [$attr->id => 'Red'],
    ];

    $product = $service->createProduct($data);

    echo "Created product id: {$product->id}\n";

    $vals = ProductAttributeValue::where('product_id', $product->id)->get();
    foreach ($vals as $v) {
        echo "AttributeValue row -> id: {$v->id}, attribute_id: {$v->attribute_id}, value: {$v->value}\n";
    }

    echo "Test completed.\n";
} catch (Exception $e) {
    echo "Error during test: " . $e->getMessage() . "\n";
}

return 0;
