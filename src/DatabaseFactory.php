<?php

namespace App;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

final class DatabaseFactory
{
    public static function create(array $config): Connection
    {
        $host = ($config['host'] === 'localhost') ? '127.0.0.1' : $config['host'];
        return DriverManager::getConnection([
            'driver' => 'pdo_mysql',
            'host' => $host,
            'port' => (int) $config['port'],
            'dbname' => $config['name'],
            'user' => $config['user'],
            'password' => $config['pass'],
            'charset' => 'utf8mb4',
        ]);
    }
}
