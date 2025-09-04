<?php
declare(strict_types=1);

namespace Core;

use Http\Request;
use Http\Response;

class Router
{
    private array $routes = [];

    public function get(string $pattern, callable|array $callback): void
    {
        $this->add(HttpMethod::GET, $pattern, $callback);
    }

    public function post(string $pattern, callable|array $callback): void
    {
        $this->add(HttpMethod::POST, $pattern, $callback);
    }

    private function add(HttpMethod $method, string $pattern, callable|array $callback): void
    {
        $pattern = '#^' . preg_replace('#\{([a-zA-Z0-9_]+)\}#', '(?P<$1>[a-zA-Z0-9_]+)', $pattern) . '$#';
        $this->routes[] = [$method, $pattern, $callback];
    }

    public function dispatch(Request $request, Response $response): void
    {
        $path = parse_url($request->uri, PHP_URL_PATH);
        $methodEnum = HttpMethod::tryFrom($request->method);
        foreach ($this->routes as [$routeMethod, $pattern, $callback]) {
            if ($methodEnum === $routeMethod && preg_match($pattern, $path, $matches)) {
                $params = array_values(array_filter($matches, fn($k) => is_string($k), ARRAY_FILTER_USE_KEY));
                $args = array_merge([$request, $response], $params);
                if (is_array($callback)) {
                    $controller = new $callback[0];
                    call_user_func_array([$controller, $callback[1]], $args);
                } else {
                    call_user_func_array($callback, $args);
                }
                return;
            }
        }
        $response->setStatus(404)->setBody('Не найдено')->send();
    }
}
