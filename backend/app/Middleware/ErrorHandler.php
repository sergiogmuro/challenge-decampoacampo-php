<?php

namespace App\Middleware;

use PDOException;
use Src\middleware\MiddlewareInterface;
use Src\View;

readonly class ErrorHandler implements MiddlewareInterface
{
    public function __construct(private View $view)
    {
    }

    public function handle(\Throwable $exception)
    {
        $headers = getallheaders();

        $accept = $headers['Accept'] ?? $_SERVER['HTTP_ACCEPT'] ?? '';
        $userAgent = $headers['User-Agent'] ?? $_SERVER['HTTP_USER_AGENT'] ?? '';
        $requestedWith = $headers['X-Requested-With'] ?? $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '';
        $fetchMode = $headers['Sec-Fetch-Mode'] ?? $_SERVER['HTTP_SEC_FETCH_MODE'] ?? '';

        $isPostman = (strpos($userAgent, 'PostmanRuntime') !== false);

        $isFetchOrAjax = (strtolower($requestedWith) === 'xmlhttprequest') ||
            ($fetchMode === 'cors' || $fetchMode === 'same-origin');

        $wantsJson = (strpos($accept, 'application/json') !== false);


        $code = $exception->getCode();
        $statusCode = ($code >= 400 && $code < 600) ? $code : 500;

        $page = ($statusCode === 404) ? 'errors.404' : 'errors.500';

        if ($exception instanceof PDOException) {
            $statusCode = 500;
        }

        $responseData = [
            'message' => $exception->getMessage(),
            'code' => $statusCode,
            'trace' => $exception->getTrace(),
        ];
        if ($isPostman || $isFetchOrAjax || $wantsJson) {
            $this->view->json($responseData);
            return;
        }

        $this->view->render($page, $responseData, $statusCode);
    }
}
