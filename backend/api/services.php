<?php
/**
 * H.A.C. Renovation - API Services Endpoint (public, no auth)
 * GET: list active services for quote form / frontend
 */

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($method !== 'GET') {
    Response::error('Método no permitido', null, 405);
}

// Parámetros opcionales desde query string
$fields = null;
if (!empty($_GET['fields'])) {
    $fields = array_map('trim', explode(',', $_GET['fields']));
}
$orderBy = !empty($_GET['order_by']) ? trim($_GET['order_by']) : null;

$services = Service::active($fields, $orderBy);
Response::success('Servicios obtenidos', $services);
