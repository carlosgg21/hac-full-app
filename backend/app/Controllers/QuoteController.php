<?php
/**
 * H.A.C. Renovation - QuoteController
 */

class QuoteController
{
    /**
     * Constructor - Requerir autenticación
     */
    public function __construct()
    {
        Auth::requireAuth();
    }

    /**
     * Listar cotizaciones
     */
    public function index()
    {
        $status = $_GET['status'] ?? '';
        
        if ($status) {
            $quotes = Quote::findByStatus($status);
        } else {
            $quotes = Quote::all();
        }

        if (self::isApiRequest()) {
            Response::success('Quotes retrieved', $quotes);
        } else {
            Response::view('quotes/index', ['quotes' => $quotes, 'status' => $status]);
        }
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $clients = Client::all();
        $questions = Question::active();
        
        Response::view('quotes/create', [
            'clients' => $clients,
            'questions' => $questions
        ]);
    }

    /**
     * Guardar nueva cotización
     */
    public function store()
    {
        $data = [
            'client_id' => $_POST['client_id'] ?? 0,
            'status' => $_POST['status'] ?? 'draft',
            'total_amount' => $_POST['total_amount'] ?? 0,
            'currency' => $_POST['currency'] ?? 'MXN',
            'valid_until' => $_POST['valid_until'] ?? null,
            'notes' => $_POST['notes'] ?? '',
            'metadata' => isset($_POST['metadata']) ? json_encode($_POST['metadata']) : null
        ];

        // Respuestas del cuestionario
        if (isset($_POST['answers']) && is_array($_POST['answers'])) {
            $data['answers'] = $_POST['answers'];
        }

        if (empty($data['client_id'])) {
            if (self::isApiRequest()) {
                Response::error('Client is required');
            } else {
                $_SESSION['error'] = 'Client is required';
                Response::redirect('/quotes/create');
            }
        }

        $id = Quote::create($data);

        if (self::isApiRequest()) {
            Response::success('Quote created', ['id' => $id], 201);
        } else {
            $_SESSION['success'] = 'Quote created successfully';
            Response::redirect('/quotes/' . $id);
        }
    }

    /**
     * Mostrar cotización
     */
    public function show($id)
    {
        $quote = Quote::find($id);
        
        if (!$quote) {
            Response::notFound('Quote not found');
        }

        if (self::isApiRequest()) {
            Response::success('Quote retrieved', $quote);
        } else {
            Response::view('quotes/show', ['quote' => $quote]);
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $quote = Quote::find($id);
        
        if (!$quote) {
            Response::notFound('Quote not found');
        }

        $clients = Client::all();
        $questions = Question::active();

        Response::view('quotes/edit', [
            'quote' => $quote,
            'clients' => $clients,
            'questions' => $questions
        ]);
    }

    /**
     * Actualizar cotización
     */
    public function update($id)
    {
        $quote = Quote::find($id);
        
        if (!$quote) {
            Response::notFound('Quote not found');
        }

        $data = [
            'client_id' => $_POST['client_id'] ?? $quote['client_id'],
            'status' => $_POST['status'] ?? $quote['status'],
            'total_amount' => $_POST['total_amount'] ?? $quote['total_amount'],
            'currency' => $_POST['currency'] ?? $quote['currency'],
            'valid_until' => $_POST['valid_until'] ?? $quote['valid_until'],
            'notes' => $_POST['notes'] ?? $quote['notes'],
            'metadata' => isset($_POST['metadata']) ? json_encode($_POST['metadata']) : $quote['metadata']
        ];

        // Respuestas del cuestionario
        if (isset($_POST['answers']) && is_array($_POST['answers'])) {
            $data['answers'] = $_POST['answers'];
        }

        Quote::update($id, $data);

        if (self::isApiRequest()) {
            Response::success('Quote updated');
        } else {
            $_SESSION['success'] = 'Quote updated successfully';
            Response::redirect('/quotes/' . $id);
        }
    }

    /**
     * Eliminar cotización
     */
    public function destroy($id)
    {
        $quote = Quote::find($id);
        
        if (!$quote) {
            Response::notFound('Quote not found');
        }

        Quote::delete($id);

        if (self::isApiRequest()) {
            Response::success('Quote deleted');
        } else {
            $_SESSION['success'] = 'Quote deleted successfully';
            Response::redirect('/quotes');
        }
    }

    /**
     * Enviar cotización
     */
    public function send($id)
    {
        $quote = Quote::find($id);
        
        if (!$quote) {
            Response::notFound('Quote not found');
        }

        // Actualizar estado a 'sent'
        Quote::update($id, ['status' => 'sent']);

        // TODO: Implementar envío de email

        if (self::isApiRequest()) {
            Response::success('Quote sent');
        } else {
            $_SESSION['success'] = 'Quote sent successfully';
            Response::redirect('/quotes/' . $id);
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