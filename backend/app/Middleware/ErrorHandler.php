<?php

namespace App\Middleware;

use Src\middleware\MiddlewareInterface;
use Src\View;

readonly class ErrorHandler implements MiddlewareInterface
{
    public function __construct(private View $view)
    {
    }

    public function handle(\Throwable $exception)
    {
        $code = $exception->getCode();
        $statusCode = ($code >= 400 && $code < 600) ? $code : 500;

        $page = ($statusCode === 404) ? 'errors.404' : 'errors.500';

        $this->view->render($page, [
            'message' => $exception->getMessage(),
            'code' => $statusCode,
        ], $statusCode);
    }
}
