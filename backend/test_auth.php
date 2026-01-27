<?php
/**
 * Script de prueba para diagnosticar problemas de autenticación
 */

// Definir constantes necesarias antes de incluir archivos
if (!defined('BASE_PATH')) {
    define('BASE_PATH', __DIR__);
}
if (!defined('APP_PATH')) {
    define('APP_PATH', __DIR__ . '/app');
}
if (!defined('CONFIG_PATH')) {
    define('CONFIG_PATH', __DIR__ . '/config');
}

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Auth.php';
require_once __DIR__ . '/app/Repositories/BaseRepository.php';
require_once __DIR__ . '/app/Repositories/UserRepository.php';
require_once __DIR__ . '/app/Models/User.php';

// Configurar base de datos
$db = Database::getInstance();

echo "=== Diagnóstico de Autenticación ===\n\n";

// 1. Verificar usuario en base de datos
echo "1. Buscando usuario 'admin'...\n";
$user = User::findByUsername('admin');

if ($user) {
    echo "   ✓ Usuario encontrado\n";
    echo "   - ID: " . $user['id'] . "\n";
    echo "   - Username: " . $user['username'] . "\n";
    echo "   - Email: " . $user['email'] . "\n";
    echo "   - Is Active: " . ($user['is_active'] ? 'Sí' : 'No') . "\n";
    echo "   - Password Hash: " . substr($user['password'], 0, 20) . "...\n";
    echo "   - Hash Length: " . strlen($user['password']) . " caracteres\n";
    echo "   - Hash Format: " . (strpos($user['password'], '$2y$') === 0 ? 'BCRYPT válido' : 'NO es BCRYPT') . "\n\n";
    
    // 2. Probar verificación de contraseña
    echo "2. Probando verificación de contraseña 'admin123'...\n";
    $testPassword = 'admin123';
    $result = Auth::verifyPassword($testPassword, $user['password']);
    echo "   - Resultado: " . ($result ? '✓ VÁLIDA' : '✗ INVÁLIDA') . "\n\n";
    
    // 3. Generar nuevo hash
    echo "3. Generando nuevo hash bcrypt para 'admin123'...\n";
    $newHash = Auth::hashPassword($testPassword);
    echo "   - Nuevo Hash: " . $newHash . "\n";
    echo "   - Verificación con nuevo hash: " . (Auth::verifyPassword($testPassword, $newHash) ? '✓ VÁLIDA' : '✗ INVÁLIDA') . "\n\n";
    
    // 4. Probar autenticación completa
    echo "4. Probando autenticación completa...\n";
    $authResult = User::authenticate('admin', 'admin123');
    if ($authResult) {
        echo "   ✓ Autenticación exitosa\n";
    } else {
        echo "   ✗ Autenticación fallida\n";
        echo "   - Posibles causas:\n";
        echo "     * Hash no es bcrypt válido\n";
        echo "     * Usuario inactivo\n";
        echo "     * Contraseña incorrecta\n";
    }
    
} else {
    echo "   ✗ Usuario NO encontrado\n";
    echo "   - Verifica que el usuario 'admin' exista en la base de datos\n";
}

echo "\n=== Fin del diagnóstico ===\n";
