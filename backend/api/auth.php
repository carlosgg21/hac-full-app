<?php
/**
 * H.A.C. Renovation - API Auth Endpoint
 * Delega en AuthController (mismo patrón que clients, projects, etc.).
 */

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$input = json_decode(file_get_contents('php://input'), true) ?? [];
$body = array_merge($_POST, $input);

switch ($method) {
    case 'POST':
        $result = AuthController::attemptLogin(
            $body['username'] ?? '',
            $body['password'] ?? ''
        );
        if ($result['success']) {
            Response::success($result['message'], ['user' => $result['user']], $result['code']);
        }
        Response::error($result['message'], null, $result['code']);
        break;

    case 'DELETE':
        Auth::logout();
        Response::success('Session closed');
        break;

    case 'GET':
        if (Auth::check()) {
            Response::success('User authenticated', ['user' => Auth::user()]);
        } else {
            Response::error('Not authenticated', null, 401);
        }
        break;

    default:
        Response::error('Method not allowed', null, 405);
}