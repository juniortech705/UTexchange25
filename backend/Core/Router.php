<?php
class Router
{
    private array $routes = [];

    public function get(string $uri, string $action, array $middlewares = [], ?string $name = null): void
    {
        $this->addRoute('GET', $uri, $action, $middlewares, $name);
    }

    public function post(string $uri, string $action, array $middlewares = [], ?string $name = null): void
    {
        $this->addRoute('POST', $uri, $action, $middlewares, $name);
    }

    private function addRoute(string $method, string $uri, string $action, array $middlewares, ?string $name): void
    {
        // Transforme /annonce/{id} en regex
        $pattern = preg_replace('#\{([a-zA-Z0-9_]+)\}#', '(?P<$1>[a-zA-Z0-9_-]+)', $uri);
        $pattern = "#^" . trim($pattern, '/') . "$#";

        $this->routes[] = [
            'method' => $method,
            'uri' => $this->normalize($uri),
            'pattern' => $pattern,
            'action' => $action,
            'middlewares' => $middlewares,
            'name' => $name
        ];
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $requestUri = $this->normalize(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/');

        foreach ($this->routes as $route) {
            if ($route['method'] === $method) {
                // Test regex pour les routes avec paramètres
                if (preg_match($route['pattern'], trim($requestUri, '/'), $matches)) {

                    // Filtrer uniquement les params nommés
                    $params = array_filter(
                        $matches,
                        fn($key) => !is_int($key),
                        ARRAY_FILTER_USE_KEY
                    );

                    // Exécuter les middlewares
                    foreach ($route['middlewares'] as $middleware) {
                        if (method_exists(Middleware::class, $middleware)) {
                            Middleware::$middleware();
                        }
                    }

                    // Appel Controller@method
                    [$controllerName, $methodName] = explode('@', $route['action']);
                    $controllerPath = __DIR__ . '/../controller/' . $controllerName . '.php';

                    if (!file_exists($controllerPath)) {
                        header("Location: /500");
                        exit;
                    }

                    require_once $controllerPath;

                    if (!class_exists($controllerName)) {
                        header("Location: /500");
                        exit;
                    }

                    $controller = new $controllerName();

                    if (!method_exists($controller, $methodName)) {
                        header("Location: /500");
                        exit;
                    }

                    //Passe les params au controller
                    $controller->$methodName(...array_values($params));
                    return;
                }
            }
        }

        // Route non trouvée
        http_response_code(404);
        header("Location: /404");
        exit;
    }

    private function normalize(string $uri): string
    {
        return '/' . trim($uri, '/');
    }
}