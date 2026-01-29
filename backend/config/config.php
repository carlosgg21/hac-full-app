<?php
/**
 * H.A.C. Renovation - Configuración General
 */

// Configuración de la aplicación
define('APP_NAME', 'H.A.C. Renovation');
define('APP_VERSION', '1.0.0');
define('APP_ENV', 'development'); // development, production

// Configuración de URLs
// Detectar autom?ticamente el base path desde REQUEST_URI si est? disponible
$detectedBasePath = '/backend'; // Por defecto
if (isset($_SERVER['REQUEST_URI'])) {
    $requestUri = strtok($_SERVER['REQUEST_URI'], '?');
    if (strpos($requestUri, '/hac-tests/backend') !== false) {
        $detectedBasePath = '/hac-tests/backend';
    } elseif (strpos($requestUri, '/backend') !== false) {
        $detectedBasePath = '/backend';
    }
}

// Detectar el host y protocolo
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$baseUrl = $protocol . '://' . $host . $detectedBasePath;

define('BASE_URL', $baseUrl);
define('BASE_PATH_URL', $detectedBasePath); // Base path sin protocolo/host
define('API_BASE_URL', BASE_URL . '/api');

// Configuración de sesión
define('SESSION_LIFETIME', 3600); // 1 hora
define('SESSION_NAME', 'HAC_SESSION');

// Configuración de seguridad
define('PASSWORD_MIN_LENGTH', 8);
define('CSRF_TOKEN_NAME', 'csrf_token');

// Configuración de paginación
define('ITEMS_PER_PAGE', 20);
define('MAX_ITEMS_PER_PAGE', 100);

// Configuración de archivos
define('UPLOAD_DIR', BASE_PATH . '/public/uploads');
define('MAX_UPLOAD_SIZE', 10485760); // 10MB

// Zona horaria
date_default_timezone_set('America/Mexico_City');

// Configuración de errores según el entorno
if (APP_ENV === 'production') {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', BASE_PATH . '/logs/error.log');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// Configuración de CORS (para API)
define('CORS_ALLOWED_ORIGINS', [
    'http://localhost',
    'http://localhost:3000',
    'http://127.0.0.1',
    'http://hac-tests.test',
    'http://hac-tests.test:8080'
]);