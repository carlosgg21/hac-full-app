<?php
/**
 * H.A.C. Renovation - API Quote Request (public, no auth)
 * POST: submit a quote request from the frontend wizard
 *
 * Payload: name, email, phone, address, is_owner, service_id, privacy_policy,
 *          answers [{question_id, answer_text?, answer_value?, answer_json?}],
 *          math_question, math_answer, message
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
$serviceId = isset($input['service_id']) ? (int) $input['service_id'] : 0;
$mathQuestion = trim($input['math_question'] ?? '');
$mathAnswer = trim($input['math_answer'] ?? '');

if ($name === '' || $email === '') {
    Response::error('Name and email are required', null, 400);
}

if ($serviceId < 1) {
    Response::error('Service is required', null, 400);
}

if ($mathQuestion === '' || $mathAnswer === '') {
    Response::error('Math verification is required', null, 400);
}

// Validate math answer: only allow simple expressions like "3 + 5" (digits, spaces, +, -, *)
if (!preg_match('/^\d+\s*[\+\-\*]\s*\d+$/', $mathQuestion)) {
    Response::error('Invalid math question format', null, 400);
}
$expected = 0;
if (preg_match('/^(\d+)\s*\+\s*(\d+)$/', $mathQuestion, $m)) {
    $expected = (int)$m[1] + (int)$m[2];
} elseif (preg_match('/^(\d+)\s*-\s*(\d+)$/', $mathQuestion, $m)) {
    $expected = (int)$m[1] - (int)$m[2];
} elseif (preg_match('/^(\d+)\s*\*\s*(\d+)$/', $mathQuestion, $m)) {
    $expected = (int)$m[1] * (int)$m[2];
} else {
    Response::error('Invalid math question', null, 400);
}
if ((string)$expected !== $mathAnswer) {
    Response::error('Math verification failed. Please check your answer.', null, 400);
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

$metadata = [
    'source' => 'frontend_wizard',
    'service_id' => $serviceId,
    'is_owner' => $input['is_owner'] ?? '',
    'privacy_policy' => $input['privacy_policy'] ?? '',
    'message' => $message
];

$quoteData = [
    'status' => 'pending',
    'total_amount' => 0,
    'currency' => 'CAD',
    'valid_until' => null,
    'notes' => $message,
    'metadata' => json_encode($metadata)
];

// Convert answers from payload format to Quote::saveAnswers format [question_id => answer]
$answersForQuote = [];
foreach ($input['answers'] ?? [] as $a) {
    $qid = isset($a['question_id']) ? (int) $a['question_id'] : 0;
    if ($qid < 1) continue;
    if (isset($a['answer_json'])) {
        $val = is_array($a['answer_json']) ? $a['answer_json'] : json_decode($a['answer_json'], true);
        $answersForQuote[$qid] = $val !== null ? $val : ($a['answer_text'] ?? '');
    } elseif (isset($a['answer_value']) && $a['answer_value'] !== null && $a['answer_value'] !== '') {
        $answersForQuote[$qid] = is_numeric($a['answer_value']) ? (float)$a['answer_value'] : $a['answer_value'];
    } else {
        $answersForQuote[$qid] = $a['answer_text'] ?? '';
    }
}
$quoteData['answers'] = $answersForQuote;

$db = Database::getInstance();

try {
    $db->beginTransaction();

    $clientId = Client::create($clientData);
    $quoteData['client_id'] = (int) $clientId;
    $quoteId = Quote::create($quoteData);

    $db->commit();

    Response::success('Quote request submitted', ['id' => $quoteId, 'client_id' => $clientId], 201);
} catch (Exception $e) {
    $db->rollBack();
    error_log('Quote request error: ' . $e->getMessage());
    Response::error('Error submitting quote request. Please try again.', null, 500);
}
