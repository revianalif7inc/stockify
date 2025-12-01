<?php
// Simple test to check StockMovement
try {
    $pdo = new PDO(
        'mysql:host=127.0.0.1;dbname=stockify_db',
        'root',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $stmt = $pdo->query('SELECT COUNT(*) as cnt FROM stock_movements');
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['cnt'];

    echo "Total StockMovement records: $count\n\n";

    if ($count > 0) {
        $stmt = $pdo->query('
            SELECT sm.id, sm.type, sm.quantity, sm.created_at, p.name as product_name, u.name as user_name
            FROM stock_movements sm
            LEFT JOIN products p ON sm.product_id = p.id
            LEFT JOIN users u ON sm.user_id = u.id
            ORDER BY sm.created_at DESC
            LIMIT 10
        ');
        $movements = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "Recent movements:\n";
        foreach ($movements as $m) {
            echo "- {$m['product_name']} {$m['type']} x{$m['quantity']} by {$m['user_name']} at {$m['created_at']}\n";
        }
    } else {
        echo "No movements found in database.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>