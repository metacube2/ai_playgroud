<?php

/**
 * Helper functions available globally
 */

if (!function_exists('env')) {
    function env(string $key, $default = null)
    {
        return $_ENV[$key] ?? $default;
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        return '/' . ltrim($path, '/');
    }
}

if (!function_exists('url')) {
    function url(string $path = ''): string
    {
        $baseUrl = env('APP_URL', 'http://localhost');
        return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('redirect')) {
    function redirect(string $path): void
    {
        header("Location: {$path}");
        exit;
    }
}

if (!function_exists('old')) {
    function old(string $key, $default = '')
    {
        return $_SESSION['old'][$key] ?? $default;
    }
}

if (!function_exists('error')) {
    function error(string $key): ?string
    {
        return $_SESSION['errors'][$key][0] ?? null;
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
    }
}

if (!function_exists('dd')) {
    function dd(...$vars): void
    {
        foreach ($vars as $var) {
            var_dump($var);
        }
        die();
    }
}

if (!function_exists('formatPrice')) {
    function formatPrice($price): string
    {
        return 'CHF ' . number_format($price, 2, '.', '\'');
    }
}

if (!function_exists('formatDate')) {
    function formatDate($date): string
    {
        return date('d.m.Y', strtotime($date));
    }
}

if (!function_exists('generateSlug')) {
    function generateSlug(string $text): string
    {
        $text = mb_strtolower($text, 'UTF-8');
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
        $text = preg_replace('/[\s-]+/', '-', $text);
        return trim($text, '-');
    }
}
