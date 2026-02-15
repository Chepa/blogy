<?php

require_once __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../config/config.php';
$dbConfig = $config['db'];

$host = ($dbConfig['host'] === 'localhost') ? '127.0.0.1' : $dbConfig['host'];
$connectionParams = [
    'driver' => 'pdo_mysql',
    'host' => $host,
    'port' => (int) $dbConfig['port'],
    'dbname' => $dbConfig['name'],
    'user' => $dbConfig['user'],
    'password' => $dbConfig['pass'],
    'charset' => 'utf8mb4',
];

$db = \Doctrine\DBAL\DriverManager::getConnection($connectionParams);

$schemaPath = $argv[1] ?? __DIR__ . '/../database/schema.sql';
if (!file_exists($schemaPath)) {
    fwrite(STDERR, "Файл не найден: {$schemaPath}\n");
    exit(1);
}

$sql = str_replace("\r\n", "\n", file_get_contents($schemaPath));

$statements = array_filter(
    array_map('trim', preg_split('/;\s*\n/', $sql)),
    fn(string $s) => $s !== '' && !str_starts_with(trim($s), '--')
);

foreach ($statements as $statement) {
    $stmt = trim($statement);
    if ($stmt === '') {
        continue;
    }
    // Пропускаем CREATE DATABASE и USE — подключаемся напрямую к целевой БД
    if (preg_match('/^\s*CREATE\s+DATABASE/i', $stmt) || preg_match('/^\s*USE\s+/i', $stmt)) {
        continue;
    }
    $db->executeStatement($stmt);
}

echo "Схема успешно загружена: {$schemaPath}\n";
