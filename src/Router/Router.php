<?php

namespace App\Router;

class Router
{
    // Массив для хранения всех зарегистрированных маршрутов
    private array $routes = [];

    /**
     * Метод для добавления нового маршрута.
     *
     * @param string $method HTTP-метод (GET, POST, etc.)
     * @param string $url URL маршрута
     * @param array $callback Функция обратного вызова, которая будет вызвана при совпадении маршрута
     */
    public function addRoute(string $method, string $url, array $callback): void
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'url' => $url,
            'callback' => $callback
        ];
    }

    /**
     * Метод возвращает массив routes
     */
    public function getRoutes(): array{
        return $this->routes;
    }

    /**
     * Метод для создания регулярного выражения из URL маршрута.
     * Заменяет плейсхолдеры (например, {id}) на соответствующие регулярные выражения.
     *
     * @param string $url URL маршрута
     * @return string Регулярное выражение для сопоставления URL
     */
    private function createPattern(string $url): string
    {
        $pattern = preg_replace('/\{\w+\}/', '([^/]+)', $url);
        return "#^" . $pattern . "$#";
    }

    /**
     * Метод для запуска маршрутизатора.
     * Определяет текущий запрос и ищет подходящий маршрут.
     * Если маршрут найден, вызывается соответствующая функция обратного вызова.
     * Если маршрут не найден, возвращается HTTP-код 404.
     */
    public function run(): void
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);


        foreach ($this->routes as $route) {
            $pattern = $this->createPattern($route['url']);
            if ($route['method'] === $requestMethod && preg_match($pattern, $requestUri, $matches)) {
                array_shift($matches);
                call_user_func_array($route['callback'], $matches);
                return;
            }
        }
        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
    }

}