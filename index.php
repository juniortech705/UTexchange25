<?php
//point d'entrée de l'application
ini_set('display_errors', 1);
error_reporting(E_ALL);

function loadEnv(string $path): void
{
    if (!file_exists($path)) return;

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) continue;

        [$key, $value] = explode('=', $line, 2);

        $key = trim($key);
        $value = trim($value, " \t\n\r\0\x0B\"");

        $_ENV[$key] = $value;
        putenv("$key=$value");
    }
}
// Charger .env
loadEnv(__DIR__ . '/.env');

// Gérer les requêtes statiques (CSS, JS, images)
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$fullPath = __DIR__ . $uri;
if ($uri !== '/' && file_exists($fullPath)) {
    return false;
}

require_once __DIR__ . '/backend/Core/Session.php';
require_once __DIR__ . '/backend/Core/Middleware.php';
require_once __DIR__ . '/backend/Core/Router.php';

// Démarrer la session
Session::start();

// Init router
$router = new Router();

// Charger les routes
require __DIR__ . '/backend/Core/Routes.php';

// Dispatch
$router->dispatch();