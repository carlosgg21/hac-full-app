<?php
/**
 * Script para verificar y corregir el usuario admin
 */

// Cargar configuración
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('CORE_PATH', BASE_PATH . '/core');
define('CONFIG_PATH', BASE_PATH . '/config');

// Autoloader function (igual que en index.php)
spl_autoload_register(function ($class) {
    // Convert namespace to path
    $class = str_replace('\\', '/', $class);
    
    // Try different paths
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

// Cargar config
if (file_exists(CONFIG_PATH . '/config.php')) {
    require_once CONFIG_PATH . '/config.php';
}

echo "<h1>Verificación y Corrección de Usuario Admin</h1>";

try {
    $db = Database::getInstance();
    
    // Verificar si existe el usuario admin
    $user = User::findByUsername('admin');
    
    if ($user) {
        echo "<h2>Usuario Admin Encontrado:</h2>";
        echo "<pre>";
        echo "ID: " . $user['id'] . "\n";
        echo "Username: " . $user['username'] . "\n";
        echo "Email: " . $user['email'] . "\n";
        echo "Role: " . $user['role'] . "\n";
        echo "Is Active: " . ($user['is_active'] ? 'Sí' : 'No') . "\n";
        echo "Password Hash: " . substr($user['password'], 0, 20) . "...\n";
        echo "</pre>";
        
        // Probar autenticación
        echo "<h2>Prueba de Autenticación:</h2>";
        $testPassword = 'admin123';
        $isValid = Auth::verifyPassword($testPassword, $user['password']);
        
        if ($isValid) {
            echo "<p style='color: green; font-size: 18px;'>✅ La contraseña 'admin123' es VÁLIDA</p>";
        } else {
            echo "<p style='color: red; font-size: 18px;'>❌ La contraseña 'admin123' NO es válida</p>";
            echo "<p>Generando nuevo hash para 'admin123'...</p>";
            
            // Generar nuevo hash
            $newHash = Auth::hashPassword('admin123');
            
            // Actualizar usuario
            $db->update('users', [
                'password' => $newHash
            ], 'id = :id', ['id' => $user['id']]);
            
            echo "<p style='color: green;'>✅ Contraseña actualizada correctamente</p>";
            echo "<p>Nuevo hash: " . substr($newHash, 0, 30) . "...</p>";
            
            // Verificar de nuevo
            $user = User::findByUsername('admin');
            $isValid = Auth::verifyPassword('admin123', $user['password']);
            if ($isValid) {
                echo "<p style='color: green; font-size: 18px;'>✅ Verificación: La contraseña ahora es VÁLIDA</p>";
            }
        }
    } else {
        echo "<h2>Usuario Admin NO Encontrado</h2>";
        echo "<p>Creando usuario admin...</p>";
        
        // Crear usuario admin
        $userId = User::create([
            'username' => 'admin',
            'email' => 'admin@hacrenovation.com',
            'password' => 'admin123',
            'full_name' => 'Administrador',
            'role' => 'admin',
            'is_active' => 1
        ]);
        
        echo "<p style='color: green;'>✅ Usuario admin creado con ID: {$userId}</p>";
    }
    
    // Probar autenticación completa
    echo "<h2>Prueba de Autenticación Completa:</h2>";
    $authenticated = User::authenticate('admin', 'admin123');
    
    if ($authenticated) {
        echo "<p style='color: green; font-size: 20px;'>✅ Autenticación EXITOSA</p>";
        echo "<pre>";
        echo "Usuario autenticado:\n";
        echo "ID: " . $authenticated['id'] . "\n";
        echo "Username: " . $authenticated['username'] . "\n";
        echo "Email: " . $authenticated['email'] . "\n";
        echo "Role: " . $authenticated['role'] . "\n";
        echo "</pre>";
    } else {
        echo "<p style='color: red; font-size: 20px;'>❌ Autenticación FALLIDA</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
?>