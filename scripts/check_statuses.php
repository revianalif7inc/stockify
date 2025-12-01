<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$rows = \DB::table('stock_movements')->select('id', 'status')->limit(10)->get();
foreach ($rows as $r) {
    echo $r->id . ': ' . ($r->status ?? 'NULL') . PHP_EOL;
}
