<?php
/**
 * H.A.C. Renovation - ClientController
 */

class ClientController
{
    /**
     * Constructor - Requerir autenticación
     */
    public function __construct()
    {
        Auth::requireAuth();
    }

    /**
     * Listar clientes
     */
    public function index()
    {
        $search = $_GET['search'] ?? '';
        
        if ($search) {
            $clients = Client::search($search);
        } else {
            $clients = Client::withQuotes();
        }

        if (self::isApiRequest()) {
            Response::success('Clientes obtenidos', $clients);
        } else {
            Response::view('clients/index', ['clients' => $clients, 'search' => $search]);
        }
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $post = $_SESSION['client_form'] ?? $_POST ?? [];
        if (isset($_SESSION['client_form'])) {
            unset($_SESSION['client_form']);
        }
        Response::view('clients/create', ['post' => $post]);
    }

    /**
     * Guardar nuevo cliente
     */
    public function store()
    {
        $data = [
            'first_name' => $_POST['first_name'] ?? '',
            'last_name' => $_POST['last_name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'address' => $_POST['address'] ?? '',
            'city' => $_POST['city'] ?? '',
            'state' => $_POST['state'] ?? '',
            'zip_code' => $_POST['zip_code'] ?? '',
            'country' => $_POST['country'] ?? 'México',
            'notes' => $_POST['notes'] ?? ''
        ];

        // Validación básica
        if (empty($data['first_name']) || empty($data['last_name']) || empty($data['email'])) {
            if (self::isApiRequest()) {
                Response::error('Nombre, apellido y email son requeridos');
            } else {
                $_SESSION['error'] = 'Nombre, apellido y email son requeridos';
                $_SESSION['client_form'] = $data;
                Response::redirect('/clients/create');
            }
        }

        $id = Client::create($data);

        if (self::isApiRequest()) {
            Response::success('Cliente creado', ['id' => $id], 201);
        } else {
            $_SESSION['success'] = 'Cliente creado exitosamente';
            Response::redirect('/clients/' . $id);
        }
    }

    /**
     * Mostrar cliente
     */
    public function show($id)
    {
        $client = Client::find($id);
        
        if (!$client) {
            Response::notFound('Cliente no encontrado');
        }

        $quotes = Quote::findByClient($id);

        if (self::isApiRequest()) {
            Response::success('Cliente obtenido', ['client' => $client, 'quotes' => $quotes]);
        } else {
            Response::view('clients/show', ['client' => $client, 'quotes' => $quotes]);
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $client = Client::find($id);
        
        if (!$client) {
            Response::notFound('Cliente no encontrado');
        }

        Response::view('clients/edit', ['client' => $client]);
    }

    /**
     * Actualizar cliente
     */
    public function update($id)
    {
        $client = Client::find($id);
        
        if (!$client) {
            Response::notFound('Cliente no encontrado');
        }

        $data = [
            'first_name' => $_POST['first_name'] ?? $client['first_name'],
            'last_name' => $_POST['last_name'] ?? $client['last_name'],
            'email' => $_POST['email'] ?? $client['email'],
            'phone' => $_POST['phone'] ?? $client['phone'],
            'address' => $_POST['address'] ?? $client['address'],
            'city' => $_POST['city'] ?? $client['city'],
            'state' => $_POST['state'] ?? $client['state'],
            'zip_code' => $_POST['zip_code'] ?? $client['zip_code'],
            'country' => $_POST['country'] ?? $client['country'],
            'notes' => $_POST['notes'] ?? $client['notes']
        ];

        Client::update($id, $data);

        if (self::isApiRequest()) {
            Response::success('Cliente actualizado');
        } else {
            $_SESSION['success'] = 'Cliente actualizado exitosamente';
            Response::redirect('/clients/' . $id);
        }
    }

    /**
     * Eliminar cliente
     */
    public function destroy($id)
    {
        $client = Client::find($id);
        
        if (!$client) {
            Response::notFound('Cliente no encontrado');
        }

        Client::delete($id);

        if (self::isApiRequest()) {
            Response::success('Cliente eliminado');
        } else {
            $_SESSION['success'] = 'Cliente eliminado exitosamente';
            Response::redirect('/clients');
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