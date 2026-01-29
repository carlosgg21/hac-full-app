<?php
/**
 * H.A.C. Renovation - API Router
 */

// Evitar que avisos/errores PHP se envíen como HTML y rompan la respuesta JSON
ini_set('display_errors', '0');

// Definir constantes si no están definidas
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}
if (!defined('APP_PATH')) {
    define('APP_PATH', BASE_PATH . '/app');
}
if (!defined('CORE_PATH')) {
    define('CORE_PATH', BASE_PATH . '/core');
}
if (!defined('CONFIG_PATH')) {
    define('CONFIG_PATH', BASE_PATH . '/config');
}

// Autoloader function (si no está ya registrado)
if (!function_exists('spl_autoload_functions') || !spl_autoload_functions()) {
    spl_autoload_register(function ($class) {
        $class = str_replace('\\', '/', $class);
        $paths = [
            CORE_PATH . '/' . $class . '.php',
            APP_PATH . '/Models/' . $class . '.php',
            APP_PATH . '/Repositories/' . $class . '.php',
            APP_PATH . '/Controllers/' . $class . '.php',
            CONFIG_PATH . '/' . $class . '.php',
        ];
        foreach ($paths as $path) {
            if (file_exists($path)) {
                require_once $path;
                return;
            }
        }
    });
}

// Cargar configuración
require_once CONFIG_PATH . '/config.php';

// Headers CORS
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (in_array($origin, CORS_ALLOWED_ORIGINS)) {
    header("Access-Control-Allow-Origin: {$origin}");
}
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=utf-8');

// Manejar preflight OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Obtener ruta de API (usar BASE_PATH_URL para soportar /backend y /hac-tests/backend)
$requestUri = strtok($_SERVER['REQUEST_URI'] ?? '', '?');
$apiPrefix = (defined('BASE_PATH_URL') ? BASE_PATH_URL : '/backend') . '/api';
$apiPath = preg_replace('#^' . preg_quote($apiPrefix, '#') . '/?#', '', $requestUri);
$apiPath = trim($apiPath, '/');

// Parsear ruta: resource (ej. clients), id (opcional)
$parts = explode('/', $apiPath);
$resource = $parts[0] ?? '';
$id = (isset($parts[1]) && $parts[1] !== '') ? $parts[1] : null;

// Determinar método HTTP
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Enrutar a endpoint específico
$endpointFile = BASE_PATH . '/api/' . $resource . '.php';

if (file_exists($endpointFile)) {
    require_once $endpointFile;
} else {
    Response::error('Endpoint no encontrado', null, 404);
}