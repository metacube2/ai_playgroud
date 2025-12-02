#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

use Database\Database;
use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    echo "Starting database migrations...\n\n";
    Database::runMigrations(__DIR__ . '/database/migrations');
    echo "\n✓ All migrations completed successfully!\n";
} catch (Exception $e) {
    echo "\n✗ Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
