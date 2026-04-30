<?php
class Session
{
    // Durée d'inactivité avant expiration (secondes)
    private const LIFETIME = 1800; // 30 minutes

    //Demarrer la session
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        session_set_cookie_params([
            'lifetime' => 0,           // cookie de session (expire à la fermeture du navigateur)
            'path' => '/',
            'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'), // HTTPS pour la prod
            'httponly' => true,         // inaccessible en JavaScript
            'samesite' => 'Lax',        // protection CSRF partielle
        ]);

        session_start();
        self::checkExpiration();
        self::generateCsrfToken();
    }
    //Vérifie l'expiration de la session à chaque requête. Si expirée, détruit la session.
    private static function checkExpiration(): void
    {
        if (!isset($_SESSION['_last_activity'])) {
            $_SESSION['_last_activity'] = time();
            return;
        }

        if (time() - $_SESSION['_last_activity'] > self::LIFETIME) {
            self::destroy();
            return;
        }

        // Renouvelle le timestamp à chaque requête
        $_SESSION['_last_activity'] = time();
    }

    // CSRF : génère un token unique et sécurisé s'il n'existe pas déjà dans la session
    private static function generateCsrfToken(): void
    {
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
    }
    // retounre le csrf
    public static function csrfToken(): string
    {
        return $_SESSION['_csrf_token'] ?? '';
    }

    public static function validateCsrf(string $token): bool
    {
        return hash_equals(self::csrfToken(), $token);
    }

    // connecte l'utilisateur en stockant ses informations dans la session
    public static function login(array $user): void
    {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role_name'];
        $_SESSION['_last_activity'] = time();
    }

    public static function logout(): void
    {
        self::destroy();
    }

    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public static function userId(): ?string
    {
        return $_SESSION['user_id'] ?? null;
    }

    public static function userRole(): ?string
    {
        return $_SESSION['user_role'] ?? null;
    }

    // Stocke un message flash (affiché une seule fois). Type : success | error | warning | info
    public static function flash(string $type, string $message): void
    {
        $_SESSION['_flash'][$type][] = $message;
    }

    //Récupère et supprime tous les messages flash.
    public static function getFlashes(): array
    {
        $flashes = $_SESSION['_flash'] ?? [];
        unset($_SESSION['_flash']);
        return $flashes;
    }

    // Destruction de la session
    private static function destroy(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(), '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        session_destroy();
        //exit;
    }

    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }
}
