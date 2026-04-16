<?php

declare(strict_types=1);

namespace Core;

use PDO;

class Database
{
    private static ?PDO $pdo = null;

    private function __construct() {}

    private function __clone() {}

    public static function getInstance(): PDO
    {

        if (self::$pdo == null) {

            $host = $_ENV['DB_HOST'];
            $db   = $_ENV['DB_DATABASE'];
            $user = $_ENV['DB_USERNAME'];
            $pass = $_ENV['DB_PASSWORD'];
            $charset = 'utf8mb4';
            $dsn = "mysql:host=$host;dbname=$db;charsert=$charset"; // Data Source Name
            $pdoOptions = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch associative arrays by default
                PDO::ATTR_EMULATE_PREPARES => false, // Use native prepared statements to prevent SQL injection and return the correct data types not strings
                PDO::ATTR_PERSISTENT => true, // Persistent connection to improve performance but maybe not good for a lot of concurrent users.
            ];

            try {
                self::$pdo = new PDO($dsn, $user, $pass, $pdoOptions);
            } catch (\PDOException $ex) {
                error_log('Database connection failed: ' . $ex->getMessage());
                throw new \RuntimeException('Database connection failed');
            }
        }
        return self::$pdo;
    }
}
