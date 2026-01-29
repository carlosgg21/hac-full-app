<?php
/**
 * H.A.C. Renovation - API Questions Endpoint (public, no auth)
 * GET: list active questions for quote form; optional ?service_id=X
 */

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($method !== 'GET') {
    Response::error('Method not allowed', null, 405);
}

$serviceId = isset($_GET['service_id']) ? (int) $_GET['service_id'] : null;

if ($serviceId > 0) {
    $questions = Question::findByServiceId($serviceId);
} else {
    $questions = Question::active();
}

Response::success('Questions retrieved', $questions);
