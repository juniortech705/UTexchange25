<?php

/**
 * Middleware
 * Intercepteurs exécutés avant chaque action de controller.
 * Chaque méthode statique correspond à un middleware déclarable dans les routes.
 */
class Middleware
{
    //Middleware : utilisateur doit être connecté; redirige vers /login si la session est absente ou expirée.
    public static function auth(): void
    {
        if (!Session::isLoggedIn()) {
            // Mémorise l'URL demandée pour rediriger après login
            Session::flash('info', 'Veuillez vous connecter pour accéder à cette page.');
            Session::set('_redirect_after_login', $_SERVER['REQUEST_URI']);
            self::redirect('/login');
        }
    }
    //Middleware pour guest
    public static function guest(): void
    {
        if (Session::isLoggedIn()) {
            self::redirect('/dashboard');
        }
    }
    //Middleware : validation du token CSRF sur les requêtes POST.
    public static function csrf(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $token = $_POST['_csrf_token'] ?? '';

        if (!Session::validateCsrf($token)) {
            Session::flash('error', 'Session expirée ou requête invalide.');
            self::redirect($_SERVER['HTTP_REFERER'] ?? '/');
        }
    }

   //Middleware pour admin
    public static function admin(): void
    {
        self::auth(); // vérifie d'abord que l'user est connecté
        if (Session::userRole() !== 'admin') {
            http_response_code(403);
            self::redirect('/403');
        }
    }

    //Middleware pour moderateur
    public static function moderator(): void
    {
        self::auth();
        $role = Session::userRole();
        if (!in_array($role, ['admin', 'moderateur'])) {
            http_response_code(403);
            self::redirect('/403');
        }
    }
        //Redirige vers une URL et stoppe l'exécution.
    private static function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }
}