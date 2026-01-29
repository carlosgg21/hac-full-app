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
            Response::error('Username and password are required', null, 400);
        }

        $user = User::authenticate($username, $password);

        if ($user) {
            Auth::login($user);
            Response::success('Login successful', ['user' => Auth::user()]);
        } else {
            Response::error('Invalid credentials', null, 401);
        }
        break;

    case 'DELETE':
        // Logout
        Auth::logout();
        Response::success('Session closed');
        break;

    case 'GET':
        // Verificar autenticación
        if (Auth::check()) {
            Response::success('User authenticated', ['user' => Auth::user()]);
        } else {
            Response::error('Not authenticated', null, 401);
        }
        break;

    default:
        Response::error('Method not allowed', null, 405);
}