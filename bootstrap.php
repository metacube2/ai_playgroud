<?php

// Start session
session_start();

// Load Composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Set timezone
date_default_timezone_set('Europe/Zurich');

// Error reporting based on environment
if (env('APP_DEBUG', false)) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Load configuration
$config = [];
$configFiles = glob(__DIR__ . '/config/*.php');
foreach ($configFiles as $file) {
    $key = basename($file, '.php');
    $config[$key] = require $file;
}

// Make config globally accessible
define('CONFIG', $config);

// Helper function to access config
function config(string $key, $default = null)
{
    $keys = explode('.', $key);
    $value = CONFIG;

    foreach ($keys as $k) {
        if (!isset($value[$k])) {
            return $default;
        }
        $value = $value[$k];
    }

    return $value;
}
