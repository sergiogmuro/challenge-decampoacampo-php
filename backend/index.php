<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/functions.php';

use App\Middleware\ErrorHandler;
use Src\Container;
use Src\database\Connection;
use Src\router\Router;

try {
    $container = new Container();
    $container->set(Connection::class, Connection::getInstance());

    /** @var ErrorHandler $errorHandler */
    $errorHandler = $container->get(ErrorHandler::class);

    $router = new Router($container);

    /** Routes */
    $router->get('/', \App\Controllers\ProductController::class, 'index');
    $router->get('/productos', \App\Controllers\ProductController::class, 'list');
    $router->get('/productos/{id}', \App\Controllers\ProductController::class, 'show');
    $router->post('/productos/{id}', \App\Controllers\ProductController::class, 'store');
    $router->put('/productos/{id}', \App\Controllers\ProductController::class, 'update');
    $router->delete('/productos/{id}', \App\Controllers\ProductController::class, 'remove');

    // Execution of resolver for process routes
    $router->resolve($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
} catch (\Throwable $exception) {
    if (isset($errorHandler)) {
        $errorHandler->handle($exception);
    } else {
        http_response_code(500);
        echo $exception->getMessage();
    }
    return;
}
