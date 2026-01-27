<?php
/**
 * Archivo de prueba para verificar configuración
 */

echo "<h1>Test de Configuración</h1>";

echo "<h2>Información del Servidor:</h2>";
echo "<pre>";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'N/A') . "\n";
echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'N/A') . "\n";
echo "PHP_SELF: " . ($_SERVER['PHP_SELF'] ?? 'N/A') . "\n";
echo "DOCUMENT_ROOT: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'N/A') . "\n";
echo "REQUEST_METHOD: " . ($_SERVER['REQUEST_METHOD'] ?? 'N/A') . "\n";
echo "</pre>";

echo "<h2>Ruta Parseada:</h2>";
$route = $_GET['route'] ?? '';
$requestUri = $_SERVER['REQUEST_URI'] ?? '';

if (empty($route) && !empty($requestUri)) {
    $requestUri = strtok($requestUri, '?');
    $requestUri = str_replace('/backend', '', $requestUri);
    $requestUri = str_replace('/hac-tests/backend', '', $requestUri);
    $requestUri = trim($requestUri, '/');
    $route = $requestUri;
}

echo "<pre>";
echo "Route desde GET: " . ($_GET['route'] ?? 'vacío') . "\n";
echo "Route parseada: " . ($route ?: '/') . "\n";
echo "</pre>";

echo "<h2>Archivos Core:</h2>";
$files = [
    'core/Router.php',
    'core/Database.php',
    'core/Auth.php',
    'core/Response.php',
    'config/routes.php',
    'config/database.php'
];

echo "<ul>";
foreach ($files as $file) {
    $exists = file_exists(__DIR__ . '/' . $file);
    echo "<li>" . $file . ": " . ($exists ? "✅ Existe" : "❌ No existe") . "</li>";
}
echo "</ul>";

echo "<h2>Prueba de Conexión a BD:</h2>";
if (file_exists(__DIR__ . '/config/database.php')) {
    $dbConfig = require __DIR__ . '/config/database.php';
    try {
        $dsn = "mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['database']};charset={$dbConfig['charset']}";
        $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], $dbConfig['options']);
        echo "<p style='color: green;'>✅ Conexión a base de datos exitosa</p>";
    } catch (PDOException $e) {
        echo "<p style='color: red;'>❌ Error de conexión: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
} else {
    echo "<p style='color: orange;'>⚠️ Archivo de configuración no encontrado</p>";
}
?>