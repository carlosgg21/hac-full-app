<?php
/**
 * H.A.C. Renovation - ServiceController
 */

class ServiceController
{
    /**
     * Constructor - Requerir autenticación
     */
    public function __construct()
    {
        Auth::requireAuth();
    }

    /**
     * Listar servicios
     */
    public function index()
    {
        $services = Service::all();

        if (self::isApiRequest()) {
            Response::success('Services retrieved', $services);
        } else {
            Response::view('services/index', ['services' => $services]);
        }
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        Response::view('services/create');
    }

    /**
     * Guardar nuevo servicio
     */
    public function store()
    {
        $data = [
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? '',
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];

        // Validación básica
        if (empty($data['name'])) {
            if (self::isApiRequest()) {
                Response::error('Service name is required');
            } else {
                $_SESSION['error'] = 'Service name is required';
                Response::redirect('/services/create');
            }
        }

        $id = Service::create($data);

        if (self::isApiRequest()) {
            Response::success('Service created', ['id' => $id], 201);
        } else {
            $_SESSION['success'] = 'Service created successfully';
            Response::redirect('/services');
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $service = Service::find($id);
        
        if (!$service) {
            Response::notFound('Service not found');
        }

        Response::view('services/edit', ['service' => $service]);
    }

    /**
     * Actualizar servicio
     */
    public function update($id)
    {
        $service = Service::find($id);
        
        if (!$service) {
            Response::notFound('Service not found');
        }

        // is_active: explicit "0" or 0 → inactive; anything else sent (e.g. "1", "on" from checkbox) → active
        $isActive = 0;
        if (array_key_exists('is_active', $_POST)) {
            $isActive = ($_POST['is_active'] === '0' || $_POST['is_active'] === 0) ? 0 : 1;
        }
        $data = [
            'name' => $_POST['name'] ?? $service['name'],
            'description' => $_POST['description'] ?? $service['description'],
            'is_active' => $isActive
        ];

        Service::update($id, $data);

        if (self::isApiRequest()) {
            Response::success('Service updated');
        } else {
            $_SESSION['success'] = 'Service updated successfully';
            Response::redirect('/services');
        }
    }

    /**
     * Eliminar servicio
     */
    public function destroy($id)
    {
        $service = Service::find($id);
        
        if (!$service) {
            Response::notFound('Service not found');
        }

        Service::delete($id);

        if (self::isApiRequest()) {
            Response::success('Service deleted');
        } else {
            $_SESSION['success'] = 'Service deleted successfully';
            Response::redirect('/services');
        }
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
