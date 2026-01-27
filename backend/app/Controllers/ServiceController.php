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
            Response::success('Servicios obtenidos', $services);
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
                Response::error('El nombre del servicio es requerido');
            } else {
                $_SESSION['error'] = 'El nombre del servicio es requerido';
                Response::redirect('/services/create');
            }
        }

        $id = Service::create($data);

        if (self::isApiRequest()) {
            Response::success('Servicio creado', ['id' => $id], 201);
        } else {
            $_SESSION['success'] = 'Servicio creado exitosamente';
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
            Response::notFound('Servicio no encontrado');
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
            Response::notFound('Servicio no encontrado');
        }

        $data = [
            'name' => $_POST['name'] ?? $service['name'],
            'description' => $_POST['description'] ?? $service['description'],
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];

        Service::update($id, $data);

        if (self::isApiRequest()) {
            Response::success('Servicio actualizado');
        } else {
            $_SESSION['success'] = 'Servicio actualizado exitosamente';
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
            Response::notFound('Servicio no encontrado');
        }

        Service::delete($id);

        if (self::isApiRequest()) {
            Response::success('Servicio eliminado');
        } else {
            $_SESSION['success'] = 'Servicio eliminado exitosamente';
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
