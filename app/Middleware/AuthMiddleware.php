<?php

namespace App\Middleware;

class AuthMiddleware
{
    public function handle($params = null): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
    }
}
