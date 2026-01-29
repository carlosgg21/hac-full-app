<?php
/**
 * H.A.C. Renovation - API Clients Endpoint
 */

Auth::requireAuth();

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$input = json_decode(file_get_contents('php://input'), true) ?? [];

// Obtener ID de la ruta (usar BASE_PATH_URL para soportar /backend y /hac-tests/backend)
$requestUri = strtok($_SERVER['REQUEST_URI'] ?? '', '?');
$clientsPrefix = (defined('BASE_PATH_URL') ? BASE_PATH_URL : '/backend') . '/api/clients';
$pathAfter = preg_replace('#^' . preg_quote($clientsPrefix, '#') . '/?#', '', $requestUri);
$pathAfter = trim($pathAfter, '/');
$pathParts = explode('/', $pathAfter);
$id = (isset($pathParts[0]) && $pathParts[0] !== '') ? $pathParts[0] : null;

$controller = new ClientController();

switch ($method) {
    case 'GET':
        if ($id) {
            $controller->show($id);
        } else {
            $controller->index();
        }
        break;

    case 'POST':
        $_POST = array_merge($_POST, $input);
        $controller->store();
        break;

    case 'PUT':
    case 'PATCH':
        $_POST = array_merge($_POST, $input);
        if ($id) {
            $controller->update($id);
        } else {
            Response::error('ID required', null, 400);
        }
        break;

    case 'DELETE':
        if ($id) {
            $controller->destroy($id);
        } else {
            Response::error('ID required', null, 400);
        }
        break;

    default:
        Response::error('Method not allowed', null, 405);
}