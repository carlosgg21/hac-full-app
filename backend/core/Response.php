<?php
/**
 * H.A.C. Renovation - Response Class
 * Manejo de respuestas HTTP
 */

class Response
{
    /**
     * Enviar respuesta JSON
     */
    public static function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    /**
     * Enviar respuesta JSON de éxito
     */
    public static function success($message, $data = null, $statusCode = 200)
    {
        $response = [
            'success' => true,
            'message' => $message
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        self::json($response, $statusCode);
    }

    /**
     * Enviar respuesta JSON de error
     */
    public static function error($message, $errors = null, $statusCode = 400)
    {
        $response = [
            'success' => false,
            'message' => $message
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        self::json($response, $statusCode);
    }

    /**
     * Redirigir a otra URL
     */
    public static function redirect($url, $statusCode = 302)
    {
        // Si la URL es absoluta (http:// o https://), usarla tal cual
        if (strpos($url, 'http://') === 0 || strpos($url, 'https://') === 0) {
            $finalUrl = $url;
        } else {
            // Obtener base path (priorizar BASE_PATH_URL que se calcula al inicio)
            $basePath = self::getBasePath();
            
            // Si la URL empieza con /, agregar el base path
            if (strpos($url, '/') === 0) {
                $relativeUrl = rtrim($basePath, '/') . $url;
            } else {
                // URL relativa sin /, construir desde base path
                $relativeUrl = rtrim($basePath, '/') . '/' . $url;
            }
            
            // Siempre construir URL absoluta para evitar problemas de redirección
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $finalUrl = $protocol . '://' . $host . $relativeUrl;
        }
        
        // Debug en desarrollo
        if (defined('APP_ENV') && APP_ENV === 'development') {
            $basePathDebug = self::getBasePath();
            $debugInfo = [
                'Original URL' => $url,
                'Base Path' => $basePathDebug,
                'Final URL' => $finalUrl,
                'REQUEST_URI' => $_SERVER['REQUEST_URI'] ?? 'N/A',
                'SCRIPT_NAME' => $_SERVER['SCRIPT_NAME'] ?? 'N/A',
                'BASE_PATH_URL constant' => defined('BASE_PATH_URL') ? BASE_PATH_URL : 'NO DEFINIDA',
                'BASE_URL constant' => defined('BASE_URL') ? BASE_URL : 'NO DEFINIDA'
            ];
            
            foreach ($debugInfo as $key => $value) {
                error_log("Response::redirect - {$key}: {$value}");
            }
        }
        
        http_response_code($statusCode);
        header('Location: ' . $finalUrl);
        exit;
    }

    /**
     * Obtener el base path desde SCRIPT_NAME, REQUEST_URI o constante
     */
    private static function getBasePath()
    {
        // PRIMERO: Usar constante BASE_PATH_URL si existe (más confiable, se calcula al inicio)
        if (defined('BASE_PATH_URL')) {
            $basePath = BASE_PATH_URL;
            // Verificar que no esté vacío
            if (!empty($basePath)) {
                return $basePath;
            }
        }
        
        // SEGUNDO: Usar SCRIPT_NAME como fuente principal
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        if (!empty($scriptName)) {
            // SCRIPT_NAME será algo como /backend/index.php o /hac-tests/backend/index.php
            if (strpos($scriptName, '/hac-tests/backend') !== false) {
                return '/hac-tests/backend';
            } elseif (strpos($scriptName, '/backend') !== false) {
                // Extraer el path hasta /backend (incluyendo /backend)
                $pos = strpos($scriptName, '/backend');
                return substr($scriptName, 0, $pos + 8); // 8 = longitud de '/backend'
            }
        }
        
        // TERCERO: Intentar desde REQUEST_URI
        $requestUri = $_SERVER['REQUEST_URI'] ?? '';
        if (!empty($requestUri)) {
            $requestUri = strtok($requestUri, '?');
            
            // Detectar base path desde REQUEST_URI
            if (strpos($requestUri, '/hac-tests/backend') !== false) {
                return '/hac-tests/backend';
            } elseif (strpos($requestUri, '/backend') !== false) {
                // Extraer hasta /backend
                $pos = strpos($requestUri, '/backend');
                return substr($requestUri, 0, $pos + 8);
            }
        }
        
        // CUARTO: Si existe BASE_URL, extraer el path
        if (defined('BASE_URL')) {
            $parsed = parse_url(BASE_URL);
            if (isset($parsed['path']) && !empty($parsed['path'])) {
                return rtrim($parsed['path'], '/');
            }
        }
        
        // Por defecto, usar /backend
        return '/backend';
    }

    /**
     * Renderizar vista
     */
    public static function view($view, $data = [])
    {
        extract($data);
        
        $viewPath = APP_PATH . '/Views/' . $view . '.php';
        
        if (!file_exists($viewPath)) {
            throw new Exception("Vista no encontrada: {$view}");
        }

        require $viewPath;
    }

    /**
     * Enviar respuesta 404
     */
    public static function notFound($message = 'Recurso no encontrado')
    {
        http_response_code(404);
        if (self::isApiRequest()) {
            self::error($message, null, 404);
        } else {
            echo "<h1>404 - {$message}</h1>";
        }
        exit;
    }

    /**
     * Enviar respuesta 403
     */
    public static function forbidden($message = 'Acceso denegado')
    {
        http_response_code(403);
        if (self::isApiRequest()) {
            self::error($message, null, 403);
        } else {
            echo "<h1>403 - {$message}</h1>";
        }
        exit;
    }

    /**
     * Enviar respuesta 401
     */
    public static function unauthorized($message = 'No autorizado')
    {
        http_response_code(401);
        if (self::isApiRequest()) {
            self::error($message, null, 401);
        } else {
            Response::redirect('/login');
        }
        exit;
    }

    /**
     * Generar URL con base path
     * Helper para usar en vistas
     */
    public static function url($path = '')
    {
        // Si la URL es absoluta, retornarla sin modificar
        if (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) {
            return $path;
        }
        
        // Obtener base path
        $basePath = self::getBasePath();
        
        // Si la ruta empieza con /, agregar el base path
        if (strpos($path, '/') === 0) {
            return rtrim($basePath, '/') . $path;
        } else {
            // Ruta relativa sin /, agregar base path y /
            return rtrim($basePath, '/') . '/' . $path;
        }
    }

    /**
     * Verificar si es petición API
     */
    private static function isApiRequest()
    {
        $requestUri = $_SERVER['REQUEST_URI'] ?? '';
        return strpos($requestUri, '/api/') !== false;
    }
}