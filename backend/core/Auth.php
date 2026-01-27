<?php
/**
 * H.A.C. Renovation - Auth Class
 * Manejo de autenticación y autorización
 */

class Auth
{
    /**
     * Iniciar sesión
     */
    public static function login($user)
    {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['full_name'] = $user['full_name'] ?? '';
        $_SESSION['logged_in'] = true;
        $_SESSION['login_time'] = time();
    }

    /**
     * Cerrar sesión
     */
    public static function logout()
    {
        session_unset();
        session_destroy();
        session_start();
    }

    /**
     * Verificar si el usuario está autenticado
     */
    public static function check()
    {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    /**
     * Obtener ID del usuario actual
     */
    public static function id()
    {
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Obtener usuario actual
     */
    public static function user()
    {
        if (!self::check()) {
            return null;
        }

        return [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'email' => $_SESSION['email'],
            'role' => $_SESSION['role'],
            'full_name' => $_SESSION['full_name'] ?? ''
        ];
    }

    /**
     * Verificar si el usuario tiene un rol específico
     */
    public static function hasRole($role)
    {
        if (!self::check()) {
            return false;
        }

        $userRole = $_SESSION['role'] ?? '';
        
        // Jerarquía de roles
        $hierarchy = [
            'viewer' => 1,
            'manager' => 2,
            'admin' => 3
        ];

        $userLevel = $hierarchy[$userRole] ?? 0;
        $requiredLevel = $hierarchy[$role] ?? 0;

        return $userLevel >= $requiredLevel;
    }

    /**
     * Verificar si el usuario es administrador
     */
    public static function isAdmin()
    {
        return self::hasRole('admin');
    }

    /**
     * Verificar si el usuario es manager o superior
     */
    public static function isManager()
    {
        return self::hasRole('manager');
    }

    /**
     * Requerir autenticación (redirige si no está autenticado)
     */
    public static function requireAuth()
    {
        if (!self::check()) {
            Response::unauthorized();
        }
    }

    /**
     * Requerir rol específico
     */
    public static function requireRole($role)
    {
        self::requireAuth();
        
        if (!self::hasRole($role)) {
            Response::forbidden('No tiene permisos suficientes');
        }
    }

    /**
     * Verificar contraseña
     */
    public static function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * Hashear contraseña
     */
    public static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }
}