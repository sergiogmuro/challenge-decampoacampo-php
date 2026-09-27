<?php

function d(...$args)
{
    echo "<pre>";
    var_dump(...$args);
    echo "</pre>";
}

function dd(...$args)
{
    d(...$args);
    die;
}

function env($key, $default = null)
{
    if (isset($_ENV[$key])) {
        return $_ENV[$key];
    }

    if (isset($_SERVER[$key])) {
        return $_SERVER[$key];
    }

    $value = getenv($key);
    if ($value !== false) {
        return $value;
    }

    return $default;
}

function traceFormatter($trace): array
{
    $traces = [];
    foreach ($trace as $files) {
        $traces[] = join(' ', [
            'in',
            $files['file'],
            'on',
            join('', [
                $files['class'],
                $files['type'],
                $files['function'],
                '(',
                $files['line'],
                ')']),

        ]);
    }

    return $traces;
}
