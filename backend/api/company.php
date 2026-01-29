<?php
/**
 * H.A.C. Renovation - API Company Endpoint (Público)
 */

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
    Response::error('Method not allowed', null, 405);
}

// Obtener la primera compañía
$company = Company::first();

if (!$company) {
    Response::error('Compañía no encontrada', null, 404);
}

$companyInfo = Company::getInfo($company['id']);
$socialMedia = Company::getSocialMedia($company);
$infoJson = Company::getInfoJsonFields($companyInfo);

$response = [
    'id' => (int) $company['id'],
    'name' => $company['name'] ?? '',
    'logo' => $company['logo'] ?? null,
    'acronym' => $company['acronym'] ?? null,
    'slogan' => $company['slogan'] ?? null,
    'code' => $company['code'] ?? null,
    'rbq_number' => $company['rbq_number'] ?? null,
    'web_site' => $company['web_site'] ?? null,
    'email' => $company['email'] ?? null,
    'phone' => $company['phone'] ?? null,
    'other_phone_number' => $company['other_phone_number'] ?? null,
    'address' => $company['address'] ?? null,
    'social_media' => $socialMedia,
    'info' => Company::formatCompanyInfo($companyInfo, $infoJson),
];

// Headers para evitar cache del navegador
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

Response::success('Company information retrieved', $response);
