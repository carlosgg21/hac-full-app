<?php
/**
 * H.A.C. Renovation Backend
 * Main Entry Point - Router
 * 
 * This file handles all incoming requests and routes them to the appropriate
 * controller or API endpoint.
 */

// Start session
session_start();

// Define base path
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('CORE_PATH', BASE_PATH . '/core');
define('CONFIG_PATH', BASE_PATH . '/config');
define('PUBLIC_PATH', BASE_PATH . '/public');

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Autoloader function
spl_autoload_register(function ($class) {
    // Convert namespace to path
    $class = str_replace('\\', '/', $class);
    
    // Try different paths
    $paths = [
        CORE_PATH . '/' . $class . '.php',
        APP_PATH . '/Models/' . $class . '.php',
        APP_PATH . '/Repositories/' . $class . '.php',
        APP_PATH . '/Controllers/' . $class . '.php',
        APP_PATH . '/Helpers/' . $class . '.php',
        CONFIG_PATH . '/' . $class . '.php',
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Load configuration
if (file_exists(CONFIG_PATH . '/config.php')) {
    require_once CONFIG_PATH . '/config.php';
}

// Get route from query string or REQUEST_URI
$route = $_GET['route'] ?? '';
$requestUri = $_SERVER['REQUEST_URI'] ?? '';

// Debug en desarrollo
if (defined('APP_ENV') && APP_ENV === 'development') {
    error_log("DEBUG - REQUEST_URI: " . $requestUri);
    error_log("DEBUG - GET route: " . $route);
}

// Si la ruta viene del .htaccess en el query string, usarla directamente
if (!empty($route)) {
    // La ruta ya viene parseada desde .htaccess (ej: ?route=login)
    // No hacer nada más
} elseif (!empty($requestUri)) {
    // Parse route from REQUEST_URI cuando .htaccess no funciona o acceso directo
    // Remover query string
    $requestUri = strtok($requestUri, '?');
    
    // Si estamos accediendo directamente a index.php o test.php, usar '/'
    if (strpos($requestUri, '/index.php') !== false || strpos($requestUri, '/test.php') !== false) {
        $route = '/';
    } else {
        // Remover el path base
        // Si REQUEST_URI es /backend/login, remover /backend para obtener login
        // Si REQUEST_URI es /hac-tests/backend/login, remover /hac-tests/backend para obtener login
        // Si REQUEST_URI es /login (sin backend), mantenerlo
        $originalUri = $requestUri;
        $requestUri = preg_replace('#^/hac-tests/backend/?#', '', $requestUri);
        $requestUri = preg_replace('#^/backend/?#', '', $requestUri);
        $requestUri = trim($requestUri, '/');
        
        // Si después de remover el path base queda vacío o es un archivo PHP, usar '/'
        if (empty($requestUri) || preg_match('/\.php$/', $requestUri)) {
            $route = '/';
        } else {
            $route = $requestUri;
        }
        
        // Debug
        if (defined('APP_ENV') && APP_ENV === 'development') {
            error_log("DEBUG - Original URI: " . $originalUri);
            error_log("DEBUG - Parsed route: " . $route);
        }
    }
} else {
    $route = '/';
}

// Determine if this is an API request
$isApiRequest = strpos($route, 'api/') === 0 || strpos($requestUri, '/api/') !== false;

if ($isApiRequest) {
    // Route to API handler
    $apiPath = BASE_PATH . '/api/index.php';
    if (file_exists($apiPath)) {
        require_once $apiPath;
    } else {
        // API not implemented yet
        header('Content-Type: application/json');
        http_response_code(501);
        echo json_encode([
            'error' => 'API not implemented',
            'message' => 'API endpoints are not yet available'
        ]);
    }
} else {
    // Route to MVC controller
    // For now, show a simple message
    // TODO: Implement Router class and proper routing
    
    if (file_exists(CORE_PATH . '/Router.php')) {
        require_once CORE_PATH . '/Router.php';
        $router = new Router();
        $router->dispatch($route);
    } else {
        // Router not implemented yet - show status page
        header('Content-Type: text/html; charset=utf-8');
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>H.A.C. Renovation - Backend Status</title>
            <style>
                body {
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
                    max-width: 800px;
                    margin: 50px auto;
                    padding: 20px;
                    background: #f5f5f5;
                }
                .container {
                    background: white;
                    padding: 30px;
                    border-radius: 8px;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                }
                h1 {
                    color: #1e3a5f;
                    margin-top: 0;
                }
                .status {
                    padding: 15px;
                    margin: 15px 0;
                    border-radius: 5px;
                    border-left: 4px solid;
                }
                .status.success {
                    background: #d4edda;
                    border-color: #28a745;
                    color: #155724;
                }
                .status.warning {
                    background: #fff3cd;
                    border-color: #ffc107;
                    color: #856404;
                }
                .status.info {
                    background: #d1ecf1;
                    border-color: #17a2b8;
                    color: #0c5460;
                }
                ul {
                    line-height: 1.8;
                }
                code {
                    background: #f4f4f4;
                    padding: 2px 6px;
                    border-radius: 3px;
                    font-family: 'Courier New', monospace;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>🚧 H.A.C. Renovation Backend</h1>
                
                <div class="status success">
                    <strong>✅ Estructura de Directorios:</strong> Correctamente creada
                </div>
                
                <div class="status warning">
                    <strong>⚠️ Estado Actual:</strong> Backend en desarrollo
                </div>
                
                <div class="status info">
                    <strong>ℹ️ Información:</strong>
                    <ul>
                        <li>La estructura de directorios está correctamente organizada</li>
                        <li>El patrón Repository ha sido agregado correctamente</li>
                        <li>Los archivos PHP necesitan ser implementados</li>
                        <li>Ver <code>ESTRUCTURA_EVALUACION.md</code> para detalles completos</li>
                    </ul>
                </div>
                
                <h2>Próximos Pasos:</h2>
                <ol>
                    <li>Implementar <code>core/Router.php</code></li>
                    <li>Implementar <code>core/Database.php</code></li>
                    <li>Crear <code>config/database.php</code></li>
                    <li>Implementar Repositories</li>
                    <li>Implementar Models</li>
                    <li>Implementar Controllers</li>
                    <li>Crear Views</li>
                    <li>Implementar API endpoints</li>
                </ol>
            </div>
        </body>
        </html>
        <?php
    }
}
