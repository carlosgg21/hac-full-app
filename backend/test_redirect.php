<?php
/**
 * Test de redirección simulando POST
 */

// Cargar configuración
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('CORE_PATH', BASE_PATH . '/core');
define('CONFIG_PATH', BASE_PATH . '/config');

// Simular variables de servidor como en un POST real
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['REQUEST_URI'] = '/backend/login';
$_SERVER['SCRIPT_NAME'] = '/backend/index.php';
$_SERVER['HTTP_HOST'] = 'hac-tests.test:8080';
$_SERVER['HTTPS'] = '';

// Cargar config
if (file_exists(CONFIG_PATH . '/config.php')) {
    require_once CONFIG_PATH . '/config.php';
}

// Cargar Response
if (file_exists(CORE_PATH . '/Response.php')) {
    require_once CORE_PATH . '/Response.php';
}

echo "<h1>Test de Redirección (Simulando POST)</h1>";

echo "<h2>Variables del Servidor Simuladas:</h2>";
echo "<pre>";
echo "REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD'] . "\n";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "HTTP_HOST: " . $_SERVER['HTTP_HOST'] . "\n";
echo "</pre>";

echo "<h2>Constantes:</h2>";
echo "<pre>";
echo "BASE_PATH_URL: " . (defined('BASE_PATH_URL') ? BASE_PATH_URL : 'NO DEFINIDA') . "\n";
echo "BASE_URL: " . (defined('BASE_URL') ? BASE_URL : 'NO DEFINIDA') . "\n";
echo "</pre>";

if (class_exists('Response')) {
    // Usar reflexión para llamar al método privado
    $reflection = new ReflectionClass('Response');
    $method = $reflection->getMethod('getBasePath');
    $method->setAccessible(true);
    $basePath = $method->invoke(null);
    
    echo "<h2>Base Path Detectado:</h2>";
    echo "<pre>{$basePath}</pre>";
    
    echo "<h2>Simulación de redirect('/dashboard'):</h2>";
    $testUrl = '/dashboard';
    $basePath = $method->invoke(null);
    $relativeUrl = rtrim($basePath, '/') . $testUrl;
    $protocol = 'http';
    $host = $_SERVER['HTTP_HOST'];
    $finalUrl = $protocol . '://' . $host . $relativeUrl;
    
    echo "<pre>";
    echo "URL original: {$testUrl}\n";
    echo "Base Path: {$basePath}\n";
    echo "URL relativa: {$relativeUrl}\n";
    echo "URL absoluta final: {$finalUrl}\n";
    echo "</pre>";
    
    echo "<h2>¿Es correcta?</h2>";
    if ($finalUrl === 'http://hac-tests.test:8080/backend/dashboard') {
        echo "<p style='color: green; font-size: 20px;'>✅ SÍ - La URL es correcta</p>";
    } else {
        echo "<p style='color: red; font-size: 20px;'>❌ NO - La URL debería ser: http://hac-tests.test:8080/backend/dashboard</p>";
        echo "<p style='color: red;'>Pero es: {$finalUrl}</p>";
    }
} else {
    echo "<p style='color: red;'>Clase Response no encontrada</p>";
}
?>