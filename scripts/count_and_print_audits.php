<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$count = \DB::table('audit_logs')->count();
echo "audit_logs count: {$count}\n";

$rows = \DB::table('audit_logs')->select('id', 'user_id', 'action', 'auditable_type', 'auditable_id', 'created_at')->orderBy('id', 'desc')->limit(10)->get();
if ($rows->isEmpty()) {
    echo "No audit log entries found.\n";
} else {
    foreach ($rows as $r) {
        $type = $r->auditable_type ? basename(str_replace('\\\\', '/', $r->auditable_type)) : '';
        echo $r->id . ' - ' . ($r->user_id ?? 'null') . ' - ' . $r->action . ' - ' . $type . '#' . $r->auditable_id . ' - ' . $r->created_at . PHP_EOL;
    }
}
