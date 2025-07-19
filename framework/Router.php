<?php

declare(strict_types=1);

namespace Framework;

use Exception;

class Router
{
    public static function load(string $class, string $method): void
    {
        try {
            if (!class_exists($class)) {
                throw new Exception("Classe $class nao encontrada.");
            }

            $controller = new $class();
            if (!method_exists($controller, $method)) {
                throw new Exception("Metodo $method nao encontrado na classe $class.");
            }

            call_user_func([$controller, $method]);

        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public static function run(): void
    {
        try {
            $routes = include __DIR__ . '/../routes/web.php';
            $requestMethod = Request::method();
            $requestPath = Request::path();

            if (!isset($routes[$requestMethod])) {
                throw new Exception("A rota não existe para o metodo $requestMethod.");
            }

            if (!array_key_exists($requestPath, $routes[$requestMethod])) {
                throw new Exception("A rota não existe para o caminho $requestPath.");
            }

            [$class, $method] = $routes[$requestMethod][$requestPath];

            self::load($class, $method);

        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}