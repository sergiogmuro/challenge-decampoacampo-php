<?php

namespace Src\router;

use Src\Container;

class Router
{
    public static array $routes = [];
    public function __construct(private Container $container) {}

    public static function get(string $route, string $controller, string $action): void
    {
        self::$routes['get'][$route] = [$controller, $action];
    }

    public static function post(string $route, string $controller, string $action): void
    {
        self::$routes['post'][$route] = [$controller, $action];
    }

    public static function put(string $route, string $controller, string $action): void
    {
        self::$routes['put'][$route] = [$controller, $action];
    }

    public static function delete(string $route, string $controller, string $action): void
    {
        self::$routes['delete'][$route] = [$controller, $action];
    }

    public function resolve($requestUri, $requestMethod): void
    {
        $path = parse_url($requestUri, PHP_URL_PATH);
        $method = strtolower($requestMethod);

        if (isset(self::$routes[$method][$path])) {
            [$controller, $action] = self::$routes[$method][$path];

            $this->callAction($controller, $action);
            return;
        }

        throw new \Exception('Page not found',404);
    }

    private function callAction($controller, $action): void
    {
        $controller = $this->container->get($controller);

        $controller->$action();

        return;
    }
}
