-- =====================================================
-- Actualizar contraseña del usuario admin
-- Password: admin123
-- Hash bcrypt válido generado con password_hash('admin123', PASSWORD_BCRYPT)
-- =====================================================

USE `hac_renovation`;

UPDATE `users` 
SET `password` = '$2y$10$35objgHdpKx.lnZFbwyPou5OkcA2cHdDnnwjkNoqfCDP2nIzP9x/G'
WHERE `username` = 'admin';

-- Verificar que se actualizó correctamente
SELECT `id`, `username`, `email`, LEFT(`password`, 20) as `password_preview`, LENGTH(`password`) as `hash_length`
FROM `users` 
WHERE `username` = 'admin';
