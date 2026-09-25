<?php

namespace Src\middleware;

use Throwable;

interface MiddlewareInterface
{
    public function handle(Throwable $exception);
}
