<?php
/**
 * H.A.C. Renovation - API Auth Endpoint
 */

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

switch ($method) {
    case 'POST':
        // Login
        $username = $_POST['username'] ?? json_decode(file_get_contents('php://input'), true)['username'] ?? '';
        $password = $_POST['password'] ?? json_decode(file_get_contents('php://input'), true)['password'] ?? '';

        if (empty($username) || empty($password)) {
            Response::error('Usuario y contraseña son requeridos', null, 400);
        }

        $user = User::authenticate($username, $password);

        if ($user) {
            Auth::login($user);
            Response::success('Login exitoso', ['user' => Auth::user()]);
        } else {
            Response::error('Credenciales inválidas', null, 401);
        }
        break;

    case 'DELETE':
        // Logout
        Auth::logout();
        Response::success('Sesión cerrada');
        break;

    case 'GET':
        // Verificar autenticación
        if (Auth::check()) {
            Response::success('Usuario autenticado', ['user' => Auth::user()]);
        } else {
            Response::error('No autenticado', null, 401);
        }
        break;

    default:
        Response::error('Método no permitido', null, 405);
}