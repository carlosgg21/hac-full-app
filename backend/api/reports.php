<?php
/**
 * H.A.C. Renovation - API Reports Endpoint
 */

Auth::requireAuth();

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Obtener tipo de reporte de la ruta
$requestUri = $_SERVER['REQUEST_URI'] ?? '';
$parts = explode('/', trim(str_replace('/backend/api/reports', '', $requestUri), '/'));
$type = $parts[0] ?? 'index';

$controller = new ReportController();

switch ($method) {
    case 'GET':
        switch ($type) {
            case 'quotes':
                $controller->quotes();
                break;
            case 'projects':
                $controller->projects();
                break;
            default:
                $controller->index();
        }
        break;

    default:
        Response::error('Method not allowed', null, 405);
}