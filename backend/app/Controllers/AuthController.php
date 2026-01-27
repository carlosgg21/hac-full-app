<?php
/**
 * H.A.C. Renovation - AuthController
 */

class AuthController
{
    /**
     * Mostrar formulario de login
     */
    public function showLogin()
    {
        if (Auth::check()) {
            Response::redirect('/dashboard');
        }

        Response::view('auth/login');
    }

    /**
     * Procesar login
     */
    public function login()
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            if (self::isApiRequest()) {
                Response::error('Usuario y contraseña son requeridos');
            } else {
                $_SESSION['error'] = 'Usuario y contraseña son requeridos';
                Response::redirect('/login');
            }
        }

        $user = User::authenticate($username, $password);

        if ($user) {
            Auth::login($user);
            
            if (self::isApiRequest()) {
                Response::success('Login exitoso', ['user' => Auth::user()]);
            } else {
                // Debug antes de redirect
                if (defined('APP_ENV') && APP_ENV === 'development') {
                    error_log("AuthController::login - Antes de redirect");
                    error_log("AuthController::login - REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'N/A'));
                    error_log("AuthController::login - SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'N/A'));
                    error_log("AuthController::login - BASE_PATH_URL: " . (defined('BASE_PATH_URL') ? BASE_PATH_URL : 'NO DEFINIDA'));
                }
                Response::redirect('/dashboard');
            }
        } else {
            if (self::isApiRequest()) {
                Response::error('Credenciales inválidas', null, 401);
            } else {
                $_SESSION['error'] = 'Credenciales inválidas';
                Response::redirect('/login');
            }
        }
    }

    /**
     * Cerrar sesión
     */
    public function logout()
    {
        Auth::logout();
        
        if (self::isApiRequest()) {
            Response::success('Sesión cerrada');
        } else {
            Response::redirect('/login');
        }
    }

    /**
     * Verificar si es petición API
     */
    private static function isApiRequest()
    {
        $requestUri = $_SERVER['REQUEST_URI'] ?? '';
        return strpos($requestUri, '/api/') !== false;
    }
}