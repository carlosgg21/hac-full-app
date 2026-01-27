<?php
/**
 * H.A.C. Renovation - API Clients Endpoint
 */

Auth::requireAuth();

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$input = json_decode(file_get_contents('php://input'), true) ?? [];

// Obtener ID de la ruta
$requestUri = $_SERVER['REQUEST_URI'] ?? '';
$parts = explode('/', trim(str_replace('/backend/api/clients', '', $requestUri), '/'));
$id = $parts[0] ?? null;

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
            Response::error('ID requerido', null, 400);
        }
        break;

    case 'DELETE':
        if ($id) {
            $controller->destroy($id);
        } else {
            Response::error('ID requerido', null, 400);
        }
        break;

    default:
        Response::error('Método no permitido', null, 405);
}