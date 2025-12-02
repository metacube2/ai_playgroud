<?php

namespace App\Core;

class Router
{
    private array $routes = [];
    private array $middlewareStack = [];

    public function get(string $path, $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    public function put(string $path, $handler): void
    {
        $this->addRoute('PUT', $path, $handler);
    }

    public function delete(string $path, $handler): void
    {
        $this->addRoute('DELETE', $path, $handler);
    }

    public function group(array $attributes, callable $callback): void
    {
        $previousMiddleware = $this->middlewareStack;

        if (isset($attributes['middleware'])) {
            $this->middlewareStack = array_merge(
                $this->middlewareStack,
                (array) $attributes['middleware']
            );
        }

        $callback($this);

        $this->middlewareStack = $previousMiddleware;
    }

    private function addRoute(string $method, string $path, $handler): void
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
            'middleware' => $this->middlewareStack,
        ];
    }

    public function dispatch(string $requestMethod, string $requestUri): void
    {
        $requestUri = parse_url($requestUri, PHP_URL_PATH);

        foreach ($this->routes as $route) {
            if ($route['method'] !== $requestMethod) {
                continue;
            }

            $pattern = $this->convertToPattern($route['path']);

            if (preg_match($pattern, $requestUri, $matches)) {
                array_shift($matches); // Remove full match

                // Execute middleware
                foreach ($route['middleware'] as $middleware) {
                    $this->executeMiddleware($middleware);
                }

                // Execute handler
                $this->executeHandler($route['handler'], $matches);
                return;
            }
        }

        // 404 Not Found
        http_response_code(404);
        echo "404 - Page Not Found";
    }

    private function convertToPattern(string $path): string
    {
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $path);
        return '#^' . $pattern . '$#';
    }

    private function executeMiddleware(string $middleware): void
    {
        $parts = explode(':', $middleware);
        $name = $parts[0];
        $params = $parts[1] ?? null;

        $middlewareClass = "App\\Middleware\\" . ucfirst($name) . "Middleware";

        if (!class_exists($middlewareClass)) {
            throw new \RuntimeException("Middleware not found: {$middlewareClass}");
        }

        $instance = new $middlewareClass();
        $instance->handle($params);
    }

    private function executeHandler($handler, array $params): void
    {
        if (is_array($handler)) {
            [$class, $method] = $handler;
            $controller = new $class();
            call_user_func_array([$controller, $method], $params);
        } elseif (is_callable($handler)) {
            call_user_func_array($handler, $params);
        }
    }
}
