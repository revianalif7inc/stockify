<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\StockMovement;
use App\Models\AuditLog;

// Find one pending movement (today's) that is not confirmed
$movement = \DB::table('stock_movements')
    ->where(function ($q) {
        $q->whereNull('status')->orWhere('status', '!=', 'confirmed'); })
    ->orderBy('created_at', 'desc')
    ->limit(1)
    ->first();

if (!$movement) {
    echo "No pending movements found to confirm.\n";
    exit(0);
}

// Update status
\DB::table('stock_movements')->where('id', $movement->id)->update(['status' => 'confirmed', 'updated_at' => now()]);

// Insert audit log
\DB::table('audit_logs')->insert([
    'user_id' => null,
    'action' => 'simulate_confirm',
    'auditable_type' => 'App\\Models\\StockMovement',
    'auditable_id' => $movement->id,
    'data' => json_encode(['simulated' => true, 'quantity' => $movement->quantity ?? null]),
    'created_at' => now(),
    'updated_at' => now(),
]);

echo "Simulated confirmation for movement id={$movement->id}\n";
