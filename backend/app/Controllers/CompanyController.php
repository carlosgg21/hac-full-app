<?php
/**
 * H.A.C. Renovation - CompanyController
 */

class CompanyController
{
    /**
     * Constructor - Requerir autenticación
     */
    public function __construct()
    {
        Auth::requireAuth();
    }

    /**
     * Mostrar formulario de edición de la compañía del sistema
     * Solo hay una compañía en el sistema, así que mostramos directamente el formulario
     */
    public function index()
    {
        // Obtener la primera (y única) compañía del sistema
        $company = Company::first();
        
        if (!$company) {
            // Si no hay compañía, crear una por defecto
            $defaultCompany = [
                'name' => 'HAC',
                'acronym' => 'HAC',
                'slogan' => 'Transformamos espacios, creamos hogares',
                'code' => 'HAC-001',
                'web_site' => 'https://www.hacrenovation.com',
                'email' => 'contacto@hacrenovation.com',
                'phone' => '+52 55 1234 5678',
                'social_media' => [
                    'facebook' => '',
                    'instagram' => '',
                    'twitter' => '',
                    'linkedin' => '',
                    'youtube' => ''
                ]
            ];
            $companyId = Company::create($defaultCompany);
            $company = Company::find($companyId);
        }

        if (self::isApiRequest()) {
            Response::success('Empresa obtenida', $company);
        } else {
            $socialMedia = Company::getSocialMedia($company);
            $companyInfo = Company::getInfo($company['id']);
            $infoJsonFields = Company::getInfoJsonFields($companyInfo);
            
            Response::view('companies/index', [
                'company' => $company,
                'socialMedia' => $socialMedia,
                'companyInfo' => $companyInfo,
                'infoJsonFields' => $infoJsonFields
            ]);
        }
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        Response::view('companies/create');
    }

    /**
     * Guardar nueva empresa
     */
    public function store()
    {
        $data = [
            'name' => $_POST['name'] ?? '',
            'logo' => $_POST['logo'] ?? '',
            'acronym' => $_POST['acronym'] ?? '',
            'slogan' => $_POST['slogan'] ?? '',
            'code' => $_POST['code'] ?? '',
            'rbq_number' => $_POST['rbq_number'] ?? '',
            'web_site' => $_POST['web_site'] ?? '',
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'other_phone_number' => $_POST['other_phone_number'] ?? '',
            'address' => $_POST['address'] ?? ''
        ];

        // Social media
        $socialMedia = [
            'facebook' => $_POST['social_facebook'] ?? '',
            'instagram' => $_POST['social_instagram'] ?? '',
            'twitter' => $_POST['social_twitter'] ?? '',
            'linkedin' => $_POST['social_linkedin'] ?? '',
            'youtube' => $_POST['social_youtube'] ?? ''
        ];
        $data['social_media'] = $socialMedia;

        // Validación básica
        if (empty($data['name'])) {
            if (self::isApiRequest()) {
                Response::error('El nombre de la empresa es requerido');
            } else {
                $_SESSION['error'] = 'El nombre de la empresa es requerido';
                Response::redirect('/companies/create');
            }
        }

        $id = Company::create($data);

        // Crear company_info si se proporciona
        if (isset($_POST['years_experience']) || isset($_POST['total_projects'])) {
            $infoData = self::prepareInfoData($_POST);
            if (!empty($infoData)) {
                Company::updateInfo($id, $infoData);
            }
        }

        if (self::isApiRequest()) {
            Response::success('Empresa creada', ['id' => $id], 201);
        } else {
            $_SESSION['success'] = 'Empresa creada exitosamente';
            Response::redirect('/companies');
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $company = Company::find($id);
        
        if (!$company) {
            Response::notFound('Empresa no encontrada');
        }

        $socialMedia = Company::getSocialMedia($company);

        Response::view('companies/edit', [
            'company' => $company,
            'socialMedia' => $socialMedia
        ]);
    }

    /**
     * Actualizar empresa
     */
    public function update($id)
    {
        $company = Company::find($id);
        
        if (!$company) {
            Response::notFound('Empresa no encontrada');
        }

        $data = [
            'name' => $_POST['name'] ?? $company['name'],
            'logo' => $_POST['logo'] ?? $company['logo'],
            'acronym' => $_POST['acronym'] ?? $company['acronym'],
            'slogan' => $_POST['slogan'] ?? $company['slogan'],
            'code' => $_POST['code'] ?? $company['code'],
            'web_site' => $_POST['web_site'] ?? $company['web_site'],
            'email' => $_POST['email'] ?? $company['email'],
            'phone' => $_POST['phone'] ?? $company['phone']
        ];

        // Social media
        $socialMedia = [
            'facebook' => $_POST['social_facebook'] ?? '',
            'instagram' => $_POST['social_instagram'] ?? '',
            'twitter' => $_POST['social_twitter'] ?? '',
            'linkedin' => $_POST['social_linkedin'] ?? '',
            'youtube' => $_POST['social_youtube'] ?? ''
        ];
        $data['social_media'] = $socialMedia;

        Company::update($id, $data);

        // Actualizar company_info
        $infoData = self::prepareInfoData($_POST);
        if (!empty($infoData)) {
            Company::updateInfo($id, $infoData);
        }

        if (self::isApiRequest()) {
            Response::success('Empresa actualizada');
        } else {
            $_SESSION['success'] = 'Empresa actualizada exitosamente';
            Response::redirect('/companies');
        }
    }

    /**
     * Eliminar empresa
     */
    public function destroy($id)
    {
        $company = Company::find($id);
        
        if (!$company) {
            Response::notFound('Empresa no encontrada');
        }

        Company::delete($id);

        if (self::isApiRequest()) {
            Response::success('Empresa eliminada');
        } else {
            $_SESSION['success'] = 'Empresa eliminada exitosamente';
            Response::redirect('/companies');
        }
    }

    /**
     * Preparar datos de company_info desde POST
     */
    private static function prepareInfoData($post)
    {
        $data = [];

        // Campos numéricos
        if (isset($post['years_experience'])) {
            $data['years_experience'] = (int)($post['years_experience'] ?? 0);
        }
        if (isset($post['total_projects'])) {
            $data['total_projects'] = (int)($post['total_projects'] ?? 0);
        }
        if (isset($post['total_clients'])) {
            $data['total_clients'] = (int)($post['total_clients'] ?? 0);
        }
        if (isset($post['client_satisfaction'])) {
            $data['client_satisfaction'] = (float)($post['client_satisfaction'] ?? 0.00);
        }
        if (isset($post['team_size'])) {
            $data['team_size'] = (int)($post['team_size'] ?? 0);
        }
        if (isset($post['testimonials_count'])) {
            $data['testimonials_count'] = (int)($post['testimonials_count'] ?? 0);
        }

        // Campos de texto
        if (isset($post['about_text'])) {
            $data['about_text'] = $post['about_text'] ?? '';
        }
        if (isset($post['mission'])) {
            $data['mission'] = $post['mission'] ?? '';
        }
        if (isset($post['vision'])) {
            $data['vision'] = $post['vision'] ?? '';
        }

        // Campos JSON
        if (isset($post['service_areas'])) {
            $serviceAreas = is_array($post['service_areas']) 
                ? $post['service_areas'] 
                : array_filter(array_map('trim', explode("\n", $post['service_areas'])));
            $data['service_areas'] = $serviceAreas;
        }

        if (isset($post['values'])) {
            $values = is_array($post['values']) 
                ? $post['values'] 
                : array_filter(array_map('trim', explode("\n", $post['values'])));
            $data['values'] = $values;
        }

        return $data;
    }

    /**
     * Verificar si es petición API
     */
    private static function isApiRequest()
    {
        $requestUri = $_SERVER['REQUEST_URI'] ?? '';
        return strpos($requestUri, '/api/') !== false;
    }
}
