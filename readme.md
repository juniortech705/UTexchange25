# UTexhange25

## Description
UTexhange25 est une application web PHP utilisant une architecture MVC (Modèle-Vue-Contrôleur) personnalisée. Elle permet la gestion d'utilisateurs, d'annonces et d'autres fonctionnalités dans un environnement sécurisé.

## Architecture

L'application est structurée en deux parties principales : le backend et le frontend.

### Backend
- **controller/** : Contrôleurs qui gèrent la logique métier et les interactions avec les vues.
- **Core/** : Classes centrales du framework (Router, Session, Middleware, etc.).
- **model/** : Modèles représentant les entités de la base de données.
- **services/** : Services métier pour la logique réutilisable.

### Frontend
- **css/** : Feuilles de style pour l'interface utilisateur.
- **js/** : Scripts JavaScript pour l'interactivité côté client.
- **views/** : Templates PHP pour le rendu des pages.
- **Images/** : Ressources statiques (logos, etc.).

### Base de données
- **PgSQL/database.php** : Configuration et connexion à la base de données PostgreSQL.

## Fonctionnement général

1. **Point d'entrée** : `index.php` traite toutes les requêtes.
2. **Routage** : Le Router analyse l'URI et appelle le contrôleur approprié.
3. **Middleware** : Exécution de vérifications (authentification, autorisations, CSRF) avant l'action du contrôleur.
4. **Contrôleur** : Gère la logique, interagit avec les modèles et services, puis rend une vue.
5. **Vue** : Template PHP qui affiche le contenu final à l'utilisateur.

## Flux de traitement d'une requête (pour débutants)

Imaginons qu'un utilisateur visite la page `/users/edit/5` pour modifier un utilisateur avec l'ID 5. Voici comment l'application traite cette requête étape par étape :

### 1. Arrivée de la requête
- L'utilisateur tape l'URL dans son navigateur.
- La requête arrive sur le serveur et est dirigée vers `index.php` (grâce à la configuration du serveur web).

### 2. Chargement et routage
- `index.php` charge les fichiers nécessaires (Session, Router, etc.).
- Le Router regarde dans `backend/Core/Routes.php` et trouve la route correspondante :
  ```php
  $router->get('/users/edit/{id}', 'UserController@editForm', ['auth'], 'user.edit');
  ```
- Ici, `{id}` est un paramètre dynamique qui capture "5".

### 3. Exécution des middlewares
- Avant d'appeler le contrôleur, les middlewares sont exécutés :
  - `auth` : Vérifie si l'utilisateur est connecté. S'il ne l'est pas, redirection vers `/login`.
- Si tout est OK, on passe au contrôleur.

### 4. Appel du contrôleur
- Le Router appelle `UserController@editForm` avec le paramètre `id = 5`.
- Dans `backend/controller/UserController.php`, la méthode `editForm` est exécutée :
  ```php
  public function editForm(int $id) {
      // Utilise le service pour récupérer l'utilisateur
      $userService = new UserService();
      $user = $userService->getUserById($id);
      
      // Rend la vue avec les données
      include 'frontend/views/users/edit.php';
  }
  ```

### 5. Interaction avec les modèles et services
- Le contrôleur utilise un **Service** (ex: `UserService`) pour la logique métier.
- Le Service utilise un **Modèle** (ex: `User`) pour interagir avec la base de données.
- Exemple dans `UserService` :
  ```php
  public function getUserById(int $id) {
      $userModel = new User();
      return $userModel->findById($id);
  }
  ```
- Le Modèle (`backend/model/user.php`) contient les requêtes SQL pour récupérer/modifier les données.

### 6. Rendu de la vue
- Le contrôleur inclut un fichier de vue (ex: `frontend/views/users/edit.php`).
- La vue est un template PHP qui affiche le HTML :
  ```php
  <form action="/users/edit" method="POST">
      <input type="hidden" name="_csrf_token" value="<?php echo Session::csrfToken(); ?>">
      <input type="text" name="email" value="<?php echo $user['email']; ?>">
      <button type="submit">Modifier</button>
  </form>
  ```
- Les données récupérées (comme `$user`) sont affichées dans le formulaire.

### 7. Soumission du formulaire
- L'utilisateur remplit le formulaire et clique sur "Modifier".
- Le formulaire envoie une requête POST vers `/users/edit`.
- Le Router trouve la route POST correspondante :
  ```php
  $router->post('/users/edit', 'UserController@edit', ['auth', 'csrf']);
  ```
- Le contrôleur `UserController@edit` traite les données :
  ```php
  public function edit() {
      $userService = new UserService();
      $userService->updateUser($_POST);
      
      Session::flash('success', 'Utilisateur modifié !');
      header('Location: /users');
  }
  ```
- Après traitement, redirection vers une autre page avec un message flash.

### Résumé pour débutants
- **Modèle** : Gère les données (base de données).
- **Service** : Logique métier (calculs, validations).
- **Contrôleur** : Reçoit la requête, utilise services/modèles, décide quoi afficher.
- **Routes** : Définit quelles URLs appellent quels contrôleurs.
- **Vue** : Affiche le résultat à l'utilisateur (HTML généré par PHP).

Pour ajouter une nouvelle fonctionnalité :
1. Créez un modèle si nécessaire.
2. Ajoutez la logique dans un service.
3. Créez une méthode dans le contrôleur.
4. Définissez la route dans `Routes.php`.
5. Créez la vue correspondante.

## index.php

Le fichier `index.php` est le point d'entrée unique de l'application.

### Fonctionnalités :
- **Chargement des variables d'environnement** : Lit le fichier `.env` pour configurer l'application.
- **Gestion des fichiers statiques** : Sert directement les CSS, JS et images sans passer par le router.
- **Démarrage de la session** : Initialise la gestion des sessions utilisateur.
- **Initialisation du router** : Crée une instance du Router et charge les routes.
- **Dispatch** : Traite la requête en cours via le router.

## Router

Le Router (`backend/Core/Router.php`) gère le routage des requêtes HTTP.

### Fonctionnalités :
- **Définition des routes** : Méthodes `get()` et `post()` pour enregistrer des routes.
- **Paramètres dans l'URI** : Support des paramètres dynamiques (ex: `/users/edit/{id}`).
- **Middlewares** : Exécution de middlewares avant l'action du contrôleur.
- **Dispatch** : Recherche la route correspondante et appelle `Controller@method`.

### Exemple d'utilisation :
```php
$router->get('/users/edit/{id}', 'UserController@editForm', ['auth'], 'user.edit');
```

## Session

La classe Session (`backend/Core/Session.php`) gère les sessions utilisateur de manière sécurisée.

### Fonctionnalités :
- **Expiration automatique** : Sessions expirées après 30 minutes d'inactivité.
- **Protection CSRF** : Génération et validation de tokens CSRF pour les formulaires.
- **Authentification** : Méthodes `login()`, `logout()`, `isLoggedIn()`.
- **Messages flash** : Stockage temporaire de messages (succès, erreurs) pour affichage unique.
- **Données utilisateur** : Stockage sécurisé des informations de l'utilisateur connecté.

### Utilisation :
- Démarrage : `Session::start()`
- Vérification : `Session::isLoggedIn()`
- Messages : `Session::flash('success', 'Opération réussie')`

## Middlewares

Les middlewares (`backend/Core/Middleware.php`) sont des intercepteurs exécutés avant les actions des contrôleurs.

### Middlewares disponibles :
- **auth** : Vérifie que l'utilisateur est connecté.
- **guest** : Redirige les utilisateurs connectés (pour pages login/register).
- **csrf** : Valide le token CSRF sur les requêtes POST.
- **admin** : Vérifie le rôle administrateur.
- **moderator** : Vérifie les rôles admin ou modérateur.

## Technologies utilisées
- PHP 8+
- PostgreSQL
- HTML/CSS/JavaScript
- Architecture MVC personnalisée
