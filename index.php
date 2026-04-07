<?php
//point d'entrée de l'application
ini_set('display_errors', 1);
error_reporting(E_ALL);

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