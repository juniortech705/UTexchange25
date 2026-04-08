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

// Gestion des users
$router->get('/users', 'UserController@index', ['auth', 'admin']);
$router->get('/users/add', 'UserController@addForm', ['auth', 'admin']);
$router->post('/users/add', 'UserController@add', ['auth', 'csrf', 'admin']);
$router->get('/users/edit/{id}', 'UserController@editForm', ['auth']);
$router->post('/users/edit', 'UserController@edit', ['auth', 'csrf']);
$router->get('/users/delete/{id}', 'UserController@delete', ['auth', 'admin']);
$router->get('/users/activate/{id}', 'UserController@activate', ['auth', 'admin']);
$router->get('/users/deactivate/{id}', 'UserController@deactivate', ['auth', 'admin']);
$router->get('/users/profil/{id}', 'UserController@show', ['auth']);
$router->get('/users/pass', 'UserController@passForm', ['auth']);
$router->post('/users/pass', 'UserController@pass', ['auth']);
