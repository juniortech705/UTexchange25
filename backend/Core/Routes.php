<?php
//Lecture : méthode, URI, Controller@action, middlewares, noms.

// Page d'accueil
$router->get('/', 'HomeController@index', [], 'home');

//Erreurs
$router->get('/404', 'ErrorController@notFound', [], 'error.404');
$router->get('/403', 'ErrorController@forbidden', [], 'error.403');
$router->get('/500', 'ErrorController@internalError', [], 'error.internalError');

//Authentification
$router->get('/login', 'AuthController@loginForm', ['guest'], 'auth.login');
$router->post('/login', 'AuthController@login', ['guest', 'csrf'], 'auth.login.post');
$router->get('/register', 'AuthController@registerForm', ['guest'], 'auth.register');
$router->post('/register', 'AuthController@register', ['guest', 'csrf'], 'auth.register.post');
$router->get('/logout', 'AuthController@logout', ['auth'], 'auth.logout');

// Dashboard
$router->get('/dashboard', 'HomeController@dashboard', ['auth'], 'dashboard');

//Routes protégées

// Routes API — réponses JSON  (PHP, AJAX / jQuery)
