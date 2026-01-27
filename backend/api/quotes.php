<?php
/**
 * H.A.C. Renovation - API Quotes Endpoint
 */

Auth::requireAuth();

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$input = json_decode(file_get_contents('php://input'), true) ?? [];

// Obtener ID de la ruta
$requestUri = $_SERVER['REQUEST_URI'] ?? '';
$parts = explode('/', trim(str_replace('/backend/api/quotes', '', $requestUri), '/'));
$id = $parts[0] ?? null;
$action = $parts[1] ?? null;

$controller = new QuoteController();

switch ($method) {
    case 'GET':
        if ($action === 'send' && $id) {
            $controller->send($id);
        } elseif ($id) {
            $controller->show($id);
        } else {
            $controller->index();
        }
        break;

    case 'POST':
        $_POST = array_merge($_POST, $input);
        if ($action === 'send' && $id) {
            $controller->send($id);
        } else {
            $controller->store();
        }
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