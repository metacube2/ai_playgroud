<?php

namespace App\Middleware;

class RoleMiddleware
{
    public function handle($role = null): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        if ($role && $_SESSION['user']['role'] !== $role) {
            http_response_code(403);
            die('403 - Forbidden');
        }
    }
}
