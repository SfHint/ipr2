<?php
// Usage: php seed_api_key.php
$cfg = include __DIR__ . '/../config/config.php';
$dsn = "mysql:host={$cfg['DB_HOST']};dbname={$cfg['DB_NAME']};charset={$cfg['DB_CHAR']}";
try {
    $pdo = new PDO($dsn, $cfg['DB_USER'], $cfg['DB_PASS'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (Exception $e) {
    echo "DB connection failed: " . $e->getMessage() . PHP_EOL;
    exit(1);
}
$plain = bin2hex(random_bytes(16));
$hash = password_hash($plain, PASSWORD_DEFAULT);
$stmt = $pdo->prepare('INSERT INTO api_keys (api_key, user_id, is_active) VALUES (:api_key, NULL, 1)');
$stmt->execute([':api_key' => $hash]);
echo "Generated API key (save this somewhere secure):\n" . $plain . PHP_EOL;
echo "Inserted hashed key into database." . PHP_EOL;
