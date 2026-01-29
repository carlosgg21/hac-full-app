<?php
/**
 * H.A.C. Renovation - API Services Endpoint (public, no auth)
 * GET: list active services for quote form / frontend
 */

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($method !== 'GET') {
    Response::error('Method not allowed', null, 405);
}

// Parámetros desde query string; por defecto para API: solo id, name, description y orden por name
$fields = ['id', 'name', 'description'];
if (!empty($_GET['fields'])) {
    $fields = array_map('trim', explode(',', $_GET['fields']));
}
$orderBy = !empty($_GET['order_by']) ? trim($_GET['order_by']) : 'name ASC';

$services = Service::active($fields, $orderBy);
Response::success('Services retrieved', $services);
