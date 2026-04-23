<?php
require_once __DIR__ . '/../services/CategorieService.php';
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

            // détecter AJAX
            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

            if ($isAjax) {
                http_response_code(401);
                echo json_encode([
                    'success' => false,
                    'message' => 'Non authentifié'
                ]);
                exit;
            }

            // requête classique
            Session::flash('info', 'Veuillez vous connecter pour accéder à cette page.');

            $current = $_SERVER['REQUEST_URI'];

            if (
                !Session::get('_redirect_after_login') &&
                $current !== '/login'
            ) {
                Session::set('_redirect_after_login', $current);
            }

            self::redirect('/login');
        }
    }
    //Middleware pour guest
    public static function guest(): void
    {
        if (Session::isLoggedIn()) {
            self::redirect('/');
        }
    }
    //Middleware : validation du token CSRF sur les requêtes POST.
    public static function csrf(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        // 1. token depuis POST classique
        $token = $_POST['_csrf_token'] ?? '';

        // 2. fallback AJAX header
        if (!$token) {
            $headers = getallheaders();
            $token = $headers['X-CSRF-TOKEN']
                ?? $headers['x-csrf-token']
                ?? '';
        }

        if (!Session::validateCsrf($token)) {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                http_response_code(403);
                echo json_encode([
                    'success' => false,
                    'message' => 'CSRF invalid'
                ]);
                exit;
            }

            Session::flash('error', 'Session expirée ou requête invalide.');
            self::redirect($_SERVER['HTTP_REFERER'] ?? '/');
        }
    }

   //Middleware pour admin
    public static function admin(): void
    {
        self::auth(); // vérifie d'abord que l'user est connecté
        if (Session::userRole() !== 'Administrateur') {
            http_response_code(403);
            self::redirect('/403');
        }
    }

    //Middleware pour moderateur
    public static function moderator(): void
    {
        self::auth();
        $role = Session::userRole();
        if (!in_array($role, ['Administrateur', 'Moderateur'])) {
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

    //Middleware pour servir nav
    public static function injectGlobals(): void
    {
        // Chargé une fois, disponible dans toutes les vues via une variable globale
        if (!isset($GLOBALS['nav_categories'])) {
            $GLOBALS['nav_categories'] = CategorieService::getParentsWithChildren();
        }
    }
}