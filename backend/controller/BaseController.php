<?php
/**
 * Classe parente de tous les controllers.
 * Fournit les méthodes communes : render, json, redirect.
 */
abstract class BaseController
{
    /**
     * Rend une vue PHP en lui injectant des variables.
     * Utilise output buffering pour capturer le contenu.
     */
    protected function render(string $view, array $data = []): void
    {
        // Rend les clés du tableau accessibles comme variables dans la vue
        extract($data);

        // Récupère les flashs messages et les rend disponibles dans la vue
        $flashes = Session::getFlashes();

        $viewPath = __DIR__ . "/../../frontend/views/{$view}.php";
        if (!file_exists($viewPath)) {
            $this->redirect('/500');
        }

        require $viewPath;

    }

    //Retourne une réponse JSON.
    protected function json(mixed $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    //Redirige vers une URL.
    protected function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }

    //Retourne les données POST nettoyées.
    protected function input(string $key, mixed $default = null): mixed
    {
        $value = $_POST[$key] ?? $_GET[$key] ?? $default;
        return is_string($value) ? trim($value) : $value;
    }

    //Vérifie si la requête est de type POST.
    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    //Vérifie si la requête est une requête AJAX.
    protected function isAjax(): bool
    {
        return ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest';
    }
}