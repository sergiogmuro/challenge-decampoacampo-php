<?php

namespace Src;

use Exception;

class View
{
    private string $viewsPath;

    public function __construct()
    {
        $this->viewsPath = dirname(__DIR__) . '/app/views/';
    }

    public function render(string $view, array $content = [], int $code = 200): void
    {
        http_response_code($code);

        extract($content);

        $viewPath = str_replace('.', '/', $view);
        $file = $this->viewsPath . $viewPath . '.php';

        if (!file_exists($file)) {
            throw new Exception("View not found: {$view} in {$file}");
        }

        include $file;
    }

    public function json(array $content = [], int $code = 200): void
    {
        if (ob_get_length()) {
            ob_clean();
        }

        header('Content-Type: application/json; charset=utf-8');
        http_response_code($code);

        echo json_encode($content, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        exit;
    }
}
