<?php
/**
 * Debug de redirecciones
 */

// Cargar configuración
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('CORE_PATH', BASE_PATH . '/core');
define('CONFIG_PATH', BASE_PATH . '/config');

// Cargar config
if (file_exists(CONFIG_PATH . '/config.php')) {
    require_once CONFIG_PATH . '/config.php';
}

// Cargar Response
if (file_exists(CORE_PATH . '/Response.php')) {
    require_once CORE_PATH . '/Response.php';
}

echo "<h1>Debug de Redirección</h1>";

echo "<h2>Variables del Servidor:</h2>";
echo "<pre>";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'N/A') . "\n";
echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'N/A') . "\n";
echo "PHP_SELF: " . ($_SERVER['PHP_SELF'] ?? 'N/A') . "\n";
echo "HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'N/A') . "\n";
echo "HTTPS: " . ($_SERVER['HTTPS'] ?? 'N/A') . "\n";
echo "</pre>";

echo "<h2>Constantes Definidas:</h2>";
echo "<pre>";
echo "BASE_PATH_URL: " . (defined('BASE_PATH_URL') ? BASE_PATH_URL : 'NO DEFINIDA') . "\n";
echo "BASE_URL: " . (defined('BASE_URL') ? BASE_URL : 'NO DEFINIDA') . "\n";
echo "APP_ENV: " . (defined('APP_ENV') ? APP_ENV : 'NO DEFINIDA') . "\n";
echo "</pre>";

echo "<h2>Prueba de getBasePath():</h2>";
if (class_exists('Response')) {
    // Usar reflexión para llamar al método privado
    $reflection = new ReflectionClass('Response');
    $method = $reflection->getMethod('getBasePath');
    $method->setAccessible(true);
    $basePath = $method->invoke(null);
    
    echo "<pre>";
    echo "Base Path detectado: {$basePath}\n";
    echo "</pre>";
    
    echo "<h2>Simulación de redirect('/dashboard'):</h2>";
    $testUrl = '/dashboard';
    $basePath = $method->invoke(null);
    $finalUrl = rtrim($basePath, '/') . $testUrl;
    
    // Construir URL absoluta
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $absoluteUrl = $protocol . '://' . $host . $finalUrl;
    
    echo "<pre>";
    echo "URL original: {$testUrl}\n";
    echo "Base Path: {$basePath}\n";
    echo "URL relativa final: {$finalUrl}\n";
    echo "URL absoluta final: {$absoluteUrl}\n";
    echo "</pre>";
    
    echo "<h2>Prueba Real:</h2>";
    echo "<p><a href='{$absoluteUrl}'>Hacer redirect a: {$absoluteUrl}</a></p>";
} else {
    echo "<p style='color: red;'>Clase Response no encontrada</p>";
}
?>