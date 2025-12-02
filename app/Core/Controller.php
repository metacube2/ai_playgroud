<?php

namespace App\Core;

class Controller
{
    protected function view(string $view, array $data = []): void
    {
        extract($data);

        $viewPath = __DIR__ . '/../Views/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($viewPath)) {
            throw new \RuntimeException("View not found: {$view}");
        }

        require_once $viewPath;
    }

    protected function json($data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function redirect(string $path): void
    {
        header("Location: {$path}");
        exit;
    }

    protected function back(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->redirect($referer);
    }

    protected function input(string $key, $default = null)
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    protected function validate(array $rules): array
    {
        $errors = [];
        $data = [];

        foreach ($rules as $field => $fieldRules) {
            $value = $this->input($field);
            $fieldRules = explode('|', $fieldRules);

            foreach ($fieldRules as $rule) {
                if ($rule === 'required' && empty($value)) {
                    $errors[$field][] = ucfirst($field) . ' is required';
                }

                if (str_starts_with($rule, 'min:')) {
                    $min = (int) substr($rule, 4);
                    if (strlen($value) < $min) {
                        $errors[$field][] = ucfirst($field) . " must be at least {$min} characters";
                    }
                }

                if (str_starts_with($rule, 'max:')) {
                    $max = (int) substr($rule, 4);
                    if (strlen($value) > $max) {
                        $errors[$field][] = ucfirst($field) . " must not exceed {$max} characters";
                    }
                }

                if ($rule === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = ucfirst($field) . ' must be a valid email';
                }
            }

            $data[$field] = $value;
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $data;
            $this->back();
        }

        return $data;
    }

    protected function auth()
    {
        return $_SESSION['user'] ?? null;
    }

    protected function isAuthenticated(): bool
    {
        return isset($_SESSION['user']);
    }
}
