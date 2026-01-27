<?php
/**
 * Generar hash de contraseña para admin123
 */

$password = 'admin123';
$hash = password_hash($password, PASSWORD_BCRYPT);

echo "<h1>Generador de Hash de Contraseña</h1>";
echo "<p><strong>Contraseña:</strong> {$password}</p>";
echo "<p><strong>Hash generado:</strong></p>";
echo "<pre style='background: #f0f0f0; padding: 10px; border: 1px solid #ccc;'>{$hash}</pre>";

echo "<h2>SQL para actualizar:</h2>";
echo "<pre style='background: #f0f0f0; padding: 10px; border: 1px solid #ccc;'>";
echo "UPDATE users SET password = '{$hash}' WHERE username = 'admin';";
echo "</pre>";

echo "<h2>Verificación:</h2>";
$verify = password_verify($password, $hash);
if ($verify) {
    echo "<p style='color: green; font-size: 18px;'>✅ El hash es válido para la contraseña 'admin123'</p>";
} else {
    echo "<p style='color: red; font-size: 18px;'>❌ Error: El hash no es válido</p>";
}
?>