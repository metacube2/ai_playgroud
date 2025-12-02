<?php
/**
 * Migration: Add email column to bands table
 * Run this once to update existing databases
 */

require_once __DIR__ . '/includes/database.php';

try {
    $pdo = db();

    $columns = $pdo->query("PRAGMA table_info(bands)")->fetchAll(PDO::FETCH_ASSOC);
    $hasEmail = false;

    foreach ($columns as $column) {
        if ($column['name'] === 'email') {
            $hasEmail = true;
            break;
        }
    }

    if (!$hasEmail) {
        echo "Adding email column to bands table...\n";
        $pdo->exec("ALTER TABLE bands ADD COLUMN email TEXT");
        echo "✓ Email column added successfully!\n";
    } else {
        echo "✓ Email column already exists.\n";
    }

    echo "\nMigration completed successfully!\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
