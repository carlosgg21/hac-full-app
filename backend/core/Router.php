<?php
/**
 * H.A.C. Renovation - Router Class
 * Sistema de enrutamiento
 */

class Router
{
    private $routes = [];
    private $params = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        // Cargar rutas desde configuración
        $this->routes = require CONFIG_PATH . '/routes.php';
    }

    /**
     * Despachar ruta
     */
    public function dispatch($route = '')
    {
        // Limpiar ruta
        $route = trim($route, '/');
        
        // Si está vacía, usar dashboard
        if (empty($route)) {
            $route = '/';
        }

        // Obtener método HTTP
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        
        // Soporte para override de método (para formularios HTML que usan PUT/DELETE)
        // Si es POST y tiene _method en POST, usar ese método
        if ($method === 'POST' && isset($_POST['_method'])) {
            $overrideMethod = strtoupper($_POST['_method']);
            if (in_array($overrideMethod, ['PUT', 'DELETE', 'PATCH'])) {
                $method = $overrideMethod;
            }
        }
        
        // Buscar ruta coincidente
        $matched = $this->matchRoute($method, $route);

        if ($matched) {
            $this->executeRoute($matched);
        } else {
            Response::notFound('Ruta no encontrada: ' . $route);
        }
    }

    /**
     * Buscar ruta coincidente
     */
    private function matchRoute($method, $route)
    {
        // Normalizar ruta: agregar / al inicio si no lo tiene
        $normalizedRoute = $route;
        if ($route !== '/' && substr($route, 0, 1) !== '/') {
            $normalizedRoute = '/' . $route;
        }
        
        $routeKey = $method . ':' . $normalizedRoute;
        
        // Buscar coincidencia exacta
        if (isset($this->routes[$routeKey])) {
            return [
                'handler' => $this->routes[$routeKey],
                'params' => []
            ];
        }
        
        // También intentar sin la barra inicial
        if ($normalizedRoute !== $route) {
            $routeKey2 = $method . ':' . $route;
            if (isset($this->routes[$routeKey2])) {
                return [
                    'handler' => $this->routes[$routeKey2],
                    'params' => []
                ];
            }
        }

        // Buscar coincidencia con parámetros
        $routeToMatch = '/' . $route;
        
        foreach ($this->routes as $pattern => $handler) {
            // Extraer método y patrón
            if (preg_match('/^(GET|POST|PUT|DELETE|PATCH):(.+)$/', $pattern, $patternMatches)) {
                $routeMethod = $patternMatches[1];
                $routePattern = $patternMatches[2];

                // Verificar método
                if ($routeMethod !== $method) {
                    continue;
                }

                // Convertir patrón a regex
                $regex = $this->patternToRegex($routePattern);

                // Verificar coincidencia
                if (preg_match($regex, $routeToMatch, $routeMatches)) {
                    // Extraer parámetros
                    $params = [];
                    if (preg_match_all('/:(\w+)/', $routePattern, $paramNames)) {
                        for ($i = 0; $i < count($paramNames[1]); $i++) {
                            $params[$paramNames[1][$i]] = $routeMatches[$i + 1] ?? null;
                        }
                    }

                    return [
                        'handler' => $handler,
                        'params' => $params
                    ];
                }
            }
        }

        return null;
    }

    /**
     * Convertir patrón de ruta a regex
     */
    private function patternToRegex($pattern)
    {
        // Normalizar patrón (remover / inicial si existe)
        $pattern = trim($pattern, '/');
        
        // Dividir en segmentos
        $segments = explode('/', $pattern);
        $regexParts = [];
        
        foreach ($segments as $segment) {
            if (empty($segment)) {
                continue;
            }
            
            // Si empieza con : es parámetro
            if ($segment[0] === ':') {
                $regexParts[] = '([^/]+)';
            } else {
                // Escapar literalmente
                $regexParts[] = preg_quote($segment, '#');
            }
        }
        
        // Construir regex: debe empezar con / y terminar con $
        $regex = '#^/' . implode('/', $regexParts) . '$#';
        
        return $regex;
    }

    /**
     * Ejecutar ruta
     */
    private function executeRoute($matched)
    {
        $handler = $matched['handler'];
        $params = $matched['params'];

        if (is_array($handler) && count($handler) === 2) {
            $controllerName = $handler[0];
            $methodName = $handler[1];

            // Cargar controlador
            $controllerPath = APP_PATH . '/Controllers/' . $controllerName . '.php';
            
            if (!file_exists($controllerPath)) {
                Response::notFound('Controlador no encontrado: ' . $controllerName);
            }

            require_once $controllerPath;

            // Verificar que la clase existe
            if (!class_exists($controllerName)) {
                Response::notFound('Clase de controlador no encontrada: ' . $controllerName);
            }

            // Crear instancia y ejecutar método
            $controller = new $controllerName();
            
            if (!method_exists($controller, $methodName)) {
                Response::notFound('Método no encontrado: ' . $methodName);
            }

            // Pasar parámetros al método
            call_user_func_array([$controller, $methodName], $params);
        } else {
            Response::notFound('Handler de ruta inválido');
        }
    }

}