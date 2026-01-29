<?php
/**
 * H.A.C. Renovation - API Quote Request (public, no auth)
 * POST: submit a quote request from the frontend form / wizard
 */

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($method !== 'POST') {
    Response::error('Method not allowed', null, 405);
}

$input = json_decode(file_get_contents('php://input'), true) ?? [];
if (empty($input)) {
    $input = $_POST;
}

$name = trim($input['name'] ?? '');
$email = trim($input['email'] ?? '');
$phone = trim($input['phone'] ?? '');
$address = trim($input['address'] ?? '');
$message = trim($input['message'] ?? '');

if ($name === '' || $email === '') {
    Response::error('Name and email are required', null, 400);
}

$parts = preg_split('/\s+/', $name, 2);
$first_name = $parts[0] ?? $name;
$last_name = $parts[1] ?? '';

$clientData = [
    'first_name' => $first_name,
    'last_name' => $last_name,
    'email' => $email,
    'phone' => $phone,
    'address' => $address,
    'city' => $input['city'] ?? '',
    'state' => $input['state'] ?? '',
    'zip_code' => $input['zip_code'] ?? '',
    'country' => $input['country'] ?? 'Canada',
    'notes' => $message
];

$clientId = Client::create($clientData);

$metadata = [
    'source' => 'frontend_wizard',
    'project_type' => $input['project_type'] ?? $input['portfolio_project'] ?? '',
    'portfolio_project' => $input['portfolio_project'] ?? '',
    'property_type' => $input['property_type'] ?? '',
    'square_feet' => $input['square_feet'] ?? '',
    'budget' => $input['budget'] ?? '',
    'timeline' => $input['timeline'] ?? '',
    'preferred_contact' => $input['preferred_contact'] ?? '',
    'is_owner' => $input['is_owner'] ?? '',
    'privacy_policy' => $input['privacy_policy'] ?? '',
    'bathroom_goal' => $input['bathroom_goal'] ?? null,
    'bathroom_layout' => $input['bathroom_layout'] ?? null,
    'kitchen_goal' => $input['kitchen_goal'] ?? null,
    'kitchen_layout' => $input['kitchen_layout'] ?? null
];

$quoteData = [
    'client_id' => (int) $clientId,
    'status' => 'request',
    'total_amount' => 0,
    'currency' => 'CAD',
    'valid_until' => null,
    'notes' => $message,
    'metadata' => json_encode($metadata)
];

$quoteId = Quote::create($quoteData);

Response::success('Quote request submitted', ['id' => $quoteId, 'client_id' => $clientId], 201);
