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
            Response::success('Clients retrieved', $clients);
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
                Response::error('First name, last name and email are required');
            } else {
                $_SESSION['error'] = 'First name, last name and email are required';
                $_SESSION['client_form'] = $data;
                Response::redirect('/clients/create');
            }
        }

        $id = Client::create($data);

        if (self::isApiRequest()) {
            Response::success('Client created', ['id' => $id], 201);
        } else {
            $_SESSION['success'] = 'Client created successfully';
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
            Response::notFound('Client not found');
        }

        $quotes = Quote::findByClient($id);

        if (self::isApiRequest()) {
            Response::success('Client retrieved', ['client' => $client, 'quotes' => $quotes]);
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
            Response::notFound('Client not found');
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
            Response::notFound('Client not found');
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
            Response::success('Client updated');
        } else {
            $_SESSION['success'] = 'Client updated successfully';
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
            Response::notFound('Client not found');
        }

        Client::delete($id);

        if (self::isApiRequest()) {
            Response::success('Client deleted');
        } else {
            Response::redirect('/clients?deleted=1');
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