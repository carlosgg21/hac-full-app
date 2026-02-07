<?php
/**
 * H.A.C. Renovation - AuthController
 * Punto único de lógica de autenticación (web y API).
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
     * Intentar login (lógica compartida web + API).
     * @return array ['success' => bool, 'user' => array|null, 'message' => string, 'code' => int]
     */
    public static function attemptLogin($username, $password)
    {
        $username = trim($username ?? '');
        $password = $password ?? '';

        if ($username === '' || $password === '') {
            return [
                'success' => false,
                'user' => null,
                'message' => 'Username and password are required',
                'code' => 400,
            ];
        }

        $user = User::authenticate($username, $password);

        if ($user) {
            Auth::login($user);
            return [
                'success' => true,
                'user' => Auth::user(),
                'message' => 'Login successful',
                'code' => 200,
            ];
        }

        return [
            'success' => false,
            'user' => null,
            'message' => 'Invalid credentials',
            'code' => 401,
        ];
    }

    /**
     * Procesar login (web o API según petición)
     */
    public function login()
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $result = self::attemptLogin($username, $password);

        if (self::isApiRequest()) {
            if ($result['success']) {
                Response::success($result['message'], ['user' => $result['user']], $result['code']);
            }
            Response::error($result['message'], null, $result['code']);
        }

        if ($result['success']) {
            Response::redirect('/dashboard');
        }

        $_SESSION['error'] = $result['message'];
        Response::redirect('/login');
    }

    /**
     * Cerrar sesión (web o API según petición)
     */
    public function logout()
    {
        Auth::logout();

        if (self::isApiRequest()) {
            Response::success('Session closed');
            return;
        }

        Response::redirect('/login');
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