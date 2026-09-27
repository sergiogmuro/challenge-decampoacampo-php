<?php

namespace Src\router;

use Src\Container;

class Router
{
    public static array $routes = [];

    public function __construct(private Container $container)
    {
    }

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

        $entityBody = json_decode(file_get_contents('php://input'), true);
        $pathWithData = $this->matchDataOnPath(self::$routes[$method], $path);
        $pathWithData['variables']['body'] = $entityBody ?? [];
        if ($pathWithData) {
            [$controller, $action] = $pathWithData;

            $this->callAction($controller, $action, array_filter($pathWithData['variables'] ?? []));
            return;
        }

        throw new \Exception('Page not found', 404);
    }

    private function callAction($controller, $action, $parameters): void
    {
        $controller = $this->container->get($controller);

        $controller->$action(...$parameters);

        return;
    }

    private function matchDataOnPath($routes, $path)
    {
        foreach ($routes as $route => $controller) {
            $routesParts = array_values(array_filter(explode('/', $route)));
            $pathParts = array_values(array_filter(explode('/', $path)));

            if (count($routesParts) !== count($pathParts)) {
                continue;
            }

            $routeMatches = true;
            $variables = [];

            foreach ($routesParts as $i => $part) {
                $partReplaced = preg_replace('/(\{\w+\})/', $pathParts[$i], $part, 1);

                if ($part !== $partReplaced) {
                    $varName = str_replace('{', '', str_replace('}', '', $part));
                    $variables[$varName] = $pathParts[$i];
                } else {
                    if ($part !== $pathParts[$i]) {
                        $routeMatches = false;
                        break;
                    }
                }
            }

            if ($routeMatches) {
                $controller['variables'] = $variables;
                return $controller;
            }
        }
    }
}
