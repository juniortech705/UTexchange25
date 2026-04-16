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
$router->post('/users/delete/{id}', 'UserController@delete', ['auth', 'admin']);
$router->post('/users/activate/{id}', 'UserController@activate', ['auth', 'admin']);
$router->post('/users/deactivate/{id}', 'UserController@deactivate', ['auth', 'admin']);
$router->get('/users/profil/{id}', 'UserController@show', ['auth']);
$router->get('/users/pass', 'UserController@passForm', ['auth']);
$router->post('/users/pass', 'UserController@pass', ['auth']);

//Gestion des categories

//Gestion des annonces
$router->get('/annonces', 'AnnonceController@index');
$router->get('/annonces/{id}', 'AnnonceController@show');
$router->get('/uploads/annonces/{annonceId}/{fichier}', 'AnnonceController@servePhoto');
$router->get('/annonces/create', 'AnnonceController@addForm', ['auth']);
$router->post('/annonces/create', 'AnnonceController@add', ['auth', 'csrf']);
$router->get('/annonces/edit/{id}', 'AnnonceController@editForm', ['auth']);
$router->post('/annonces/edit/{id}', 'AnnonceController@edit', ['auth', 'csrf']);
$router->post('/annonces/delete/{id}', 'AnnonceController@delete', ['auth', 'csrf']);
$router->get('/myAnnonces', 'AnnonceController@myAnnonces', ['auth']);
$router->post('/annonces/type/{id}', 'AnnonceController@updateType', ['auth', 'csrf']);
$router->post('/annonces/status/{id}', 'AnnonceController@updateStatus', ['auth', 'csrf']);
//Photos (AJAX)
$router->post('/photos/delete/{id}', 'AnnonceController@deletePhoto', ['auth', 'csrf']);
$router->post('/photos/cover/{id}', 'AnnonceController@setCover', ['auth', 'csrf']);
//Favoris (AJAX)
$router->post('/annonces/{id}/favori', 'AnnonceController@toggleFavori', ['auth']);
$router->get('/annonces/{id}/is-favori', 'AnnonceController@isFavori', ['auth']);
$router->get('/favoris', 'AnnonceController@favoris', ['auth']);
