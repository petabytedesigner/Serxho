<?php
declare(strict_types=1);

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function app_url(string $path = ''): string
{
    $path = '/' . ltrim($path, '/');
    return rtrim(APP_URL, '/') . ($path === '/' ? '/' : $path);
}

function redirect_to(string $path): never
{
    header('Location: ' . app_url($path));
    exit;
}
