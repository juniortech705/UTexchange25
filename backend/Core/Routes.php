<?php
//Lecture : méthode, URI, Controller@action, middlewares, noms.

// Page d'accueil
$router->get('/', 'HomeController@index', [], 'home');

//Erreurs
$router->get('/404', 'ErrorController@notFound', [], 'error.404');
$router->get('/403', 'ErrorController@forbidden', [], 'error.403');
$router->get('/500', 'ErrorController@internalError', [], 'error.internalError');

//Authentification
$router->get('/login', 'AuthController@form', ['guest']);
$router->post('/login', 'AuthController@login', ['guest', 'csrf'], 'auth.login.post');
$router->post('/register', 'AuthController@register', ['guest', 'csrf'], 'auth.register.post');
$router->get('/logout', 'AuthController@logout', ['auth'], 'auth.logout');

// Dashboard
$router->get('/dashboard', 'HomeController@dashboard', ['auth', 'admin'], 'dashboard');

// Gestion des users
$router->get('/users', 'UserController@index', ['auth', 'admin']);
$router->get('/users/add', 'UserController@addForm', ['auth', 'admin']);
$router->post('/users/add', 'UserController@add', ['auth', 'csrf', 'admin']);
$router->get('/users/edit/{id}', 'UserController@editForm', ['auth']);
$router->post('/users/edit', 'UserController@edit', ['auth', 'csrf']);
$router->post('/users/delete/{id}', 'UserController@delete', ['auth', 'admin']);
$router->post('/users/activate/{id}', 'UserController@activate', ['auth', 'admin']);
$router->post('/users/deactivate/{id}', 'UserController@deactivate', ['auth', 'admin']);
$router->get('/users/profil/{id}', 'UserController@show', []);
$router->get('/users/pass', 'UserController@passForm', ['auth']);
$router->post('/users/pass', 'UserController@pass', ['auth']);

//Gestion des categories
$router->get('/categories', 'CategorieController@index', ['auth', 'admin']);
$router->get('/categories/add', 'CategorieController@addForm', ['auth', 'admin']);
$router->post('/categories/add', 'CategorieController@add', ['auth', 'csrf', 'admin']);
$router->get('/categories/edit/{id}', 'CategorieController@editForm', ['auth','admin']);
$router->post('/categories/edit/{id}', 'CategorieController@edit', ['auth', 'csrf', 'admin']);
$router->post('/categories/delete/{id}', 'CategorieController@delete', ['auth', 'admin']);
$router->post('/categories/activate/{id}', 'CategorieController@activate', ['auth', 'admin']);
$router->post('/categories/deactivate/{id}', 'CategorieController@deactivate', ['auth', 'admin']);

//Gestion des annonce
$router->get('/annonces', 'AnnonceController@index', []);
$router->get('/myAnnonces', 'AnnonceController@myAnnonces', ['auth']);
$router->get('/annonce/create', 'AnnonceController@addForm', ['auth']);
$router->post('/annonce/create', 'AnnonceController@add', ['auth', 'csrf']);
$router->get('/annonce/{id}', 'AnnonceController@show', []);
$router->get('/annonce/edit/{id}', 'AnnonceController@editForm', ['auth']);
$router->post('/annonce/edit/{id}', 'AnnonceController@edit', ['auth', 'csrf']);
$router->post('/annonce/delete/{id}', 'AnnonceController@delete', ['auth', 'csrf']);
$router->post('/annonce/type/{id}', 'AnnonceController@updateType', ['auth', 'csrf']);
$router->post('/annonce/status/{id}', 'AnnonceController@updateStatus', ['auth', 'csrf']);
$router->get('/annonce/photo/{annonceId}/{fichier}', 'AnnonceController@servePhoto');

//Photos (Ajax)
$router->post('/photos/delete/{id}', 'AnnonceController@deletePhoto', ['auth', 'csrf']);
$router->post('/photos/cover/{id}', 'AnnonceController@setCover', ['auth', 'csrf']);

//Favoris (Ajax)
$router->post('/annonce/{id}/favori', 'AnnonceController@toggleFavori', ['auth', 'csrf']);
$router->get('/annonce/{id}/is-favori', 'AnnonceController@isFavori', []);
$router->get('/favoris', 'AnnonceController@favoris', ['auth']);

//Conversations
$router->get('/conversations', 'ConversationController@index', ['auth']);
$router->post('/conversations/start', 'ConversationController@start', ['auth', 'csrf']);
$router->post('/conversations/terminate/{id}', 'ConversationController@terminate', ['auth', 'csrf']);
$router->post('/conversations/delete/{id}', 'ConversationController@delete', ['auth', 'csrf']);
$router->get('/conversations/{id}', 'ConversationController@show', ['auth']);

//Messages (Ajax)
$router->post('/conversations/{id}/messages/send', 'ConversationController@send', ['auth', 'csrf']);
$router->post('/messages/delete/{id}', 'ConversationController@deleteMessage', ['auth', 'csrf']);
$router->post('/messages/update/{id}', 'ConversationController@update', ['auth', 'csrf']);
$router->get('/conversations/{id}/messages', 'ConversationController@getMessages', ['auth']);
$router->post('/conversations/{id}/read', 'ConversationController@markAsRead', ['auth', 'csrf']);
$router->get('/messages/unread-count', 'ConversationController@countUnreadMessages', ['auth']);
$router->get('/conversations/{id}/messages/sync', 'ConversationController@syncMessages', ['auth']);

//Avis
$router->post('/conversations/{id}/avis', 'ConversationController@addAvis', ['auth', 'csrf']);
$router->post('/avis/delete/{id}', 'ConversationController@deleteAvis', ['auth', 'csrf']);

//Admin
$router->get('/adAnnonces', 'AdminController@allAnnonces', ['auth', 'admin']);
$router->get('/adAvis', 'AdminController@allAvis', ['auth', 'admin']);
$router->get('/adStats', 'AdminController@stats', ['auth', 'admin']);
$router->post('/ad/avis/deactivate', 'AdminController@deactivate', ['auth', 'admin']);
$router->post('/ad/annonce/report', 'AdminController@report', ['auth', 'admin']);