<?php
// Simple script to read .env and list tables in the configured MySQL database
$env = file_get_contents(__DIR__ . '/../.env');
$lines = explode("\n", $env);
$config = [];
foreach ($lines as $line) {
    if (trim($line) === '' || strpos(trim($line), '#') === 0)
        continue;
    if (!strpos($line, '='))
        continue;
    [$k, $v] = array_map('trim', explode('=', $line, 2));
    $v = trim($v, "\"'");
    $config[$k] = $v;
}
$host = $config['DB_HOST'] ?? '127.0.0.1';
$port = $config['DB_PORT'] ?? 3306;
$db = $config['DB_DATABASE'] ?? '';
$user = $config['DB_USERNAME'] ?? '';
$pass = $config['DB_PASSWORD'] ?? '';

echo "Connecting to DB: $host:$port / $db as $user\n";
$mysqli = new mysqli($host, $user, $pass, $db, (int) $port);
if ($mysqli->connect_errno) {
    echo "MySQL connection failed: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error . "\n";
    exit(1);
}

$result = $mysqli->query('SHOW TABLES');
if (!$result) {
    echo "SHOW TABLES failed: " . $mysqli->error . "\n";
    exit(1);
}

$tables = [];
while ($row = $result->fetch_array(MYSQLI_NUM)) {
    $tables[] = $row[0];
}

echo "Tables in $db:\n";
foreach ($tables as $t)
    echo " - $t\n";

if (in_array('product_attributes', $tables)) {
    echo "product_attributes exists.\n";
} else {
    echo "product_attributes NOT found.\n";
}

$mysqli->close();
