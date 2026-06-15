<?php
namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, array $handler): void
    {
        $this->routes[] = ['GET', $path, $handler];
    }

    public function post(string $path, array $handler): void
    {
        $this->routes[] = ['POST', $path, $handler];
    }

    public function dispatch(string $method, string $uri): void
    {
        $uri = rtrim(parse_url($uri, PHP_URL_PATH), '/') ?: '/';

        foreach ($this->routes as [$routeMethod, $path, $handler]) {
            if ($routeMethod !== $method) {
                continue;
            }
            $params = $this->match($path, $uri);
            if ($params !== null) {
                [$class, $action] = $handler;
                $controller = new $class();
                $controller->$action(...array_values($params));
                return;
            }
        }

        http_response_code(404);
        echo '<h1 style="font-family:sans-serif;padding:2rem">404 — Pagina niet gevonden</h1>';
    }

    private function match(string $route, string $uri): ?array
    {
        $route = rtrim($route, '/') ?: '/';
        $paramNames = [];

        $pattern = preg_replace_callback('/\{(\w+)\}/', function ($m) use (&$paramNames) {
            $paramNames[] = $m[1];
            return '([^/]+)';
        }, $route);

        if (!preg_match('#^' . $pattern . '$#', $uri, $matches)) {
            return null;
        }

        array_shift($matches);
        return $paramNames ? array_combine($paramNames, $matches) : [];
    }
}
