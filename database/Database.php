<?php

namespace Database;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    public static function connect(): PDO
    {
        if (self::$instance === null) {
            try {
                $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
                $port = $_ENV['DB_PORT'] ?? '3306';
                $dbname = $_ENV['DB_DATABASE'] ?? 'getyourband';
                $username = $_ENV['DB_USERNAME'] ?? 'root';
                $password = $_ENV['DB_PASSWORD'] ?? '';

                $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";

                self::$instance = new PDO($dsn, $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                throw new \RuntimeException("Database connection failed: " . $e->getMessage());
            }
        }

        return self::$instance;
    }

    public static function disconnect(): void
    {
        self::$instance = null;
    }

    public static function runMigrations(string $migrationsPath): void
    {
        $db = self::connect();
        $files = glob($migrationsPath . '/*.sql');
        sort($files);

        foreach ($files as $file) {
            echo "Running migration: " . basename($file) . "\n";
            $sql = file_get_contents($file);

            try {
                $db->exec($sql);
                echo "✓ Migration completed successfully\n";
            } catch (PDOException $e) {
                echo "✗ Migration failed: " . $e->getMessage() . "\n";
                throw $e;
            }
        }

        echo "\nAll migrations completed!\n";
    }
}
