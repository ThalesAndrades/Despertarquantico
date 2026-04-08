<?php
/**
 * Simple Router with parameter support
 */
class Router
{
    private array $routes = [];

    public function get(string $path, array $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, array $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(string $method, string $url): void
    {
        $routes = $this->routes[$method] ?? [];

        foreach ($routes as $pattern => $handler) {
            $params = $this->match($pattern, $url);
            if ($params !== false) {
                [$class, $action] = $handler;
                $controller = new $class();
                call_user_func_array([$controller, $action], $params);
                return;
            }
        }

        http_response_code(404);
        require VIEWS_PATH . '/errors/404.php';
    }

    private function match(string $pattern, string $url): array|false
    {
        $patternParts = explode('/', $pattern);
        $urlParts = explode('/', $url);

        if (count($patternParts) !== count($urlParts)) {
            return false;
        }

        $params = [];
        for ($i = 0; $i < count($patternParts); $i++) {
            if (preg_match('/^\{(\w+)\}$/', $patternParts[$i], $m)) {
                $params[$m[1]] = $urlParts[$i];
            } elseif ($patternParts[$i] !== $urlParts[$i]) {
                return false;
            }
        }

        return $params;
    }
}
