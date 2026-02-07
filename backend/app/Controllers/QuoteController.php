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

    /** Columnas por las que se puede ordenar el listado de quotes */
    private static $indexSortColumns = ['quote_number', 'client_name', 'status', 'total_amount', 'created_at'];

    /**
     * Listar cotizaciones (referencia: ClientController index)
     */
    public function index()
    {
        $search = trim($_GET['search'] ?? '');
        $status = $_GET['status'] ?? '';
        $sort = $_GET['sort'] ?? 'created_at';
        $order = strtolower($_GET['order'] ?? 'desc') === 'asc' ? 'asc' : 'desc';
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = (int)($_GET['per_page'] ?? 10);
        if (!in_array($perPage, [10, 15, 25], true)) {
            $perPage = 10;
        }
        if (!in_array($sort, self::$indexSortColumns, true)) {
            $sort = 'created_at';
        }

        $totalCount = Quote::count($status !== '' ? $status : null, $search !== '' ? $search : null);
        $totalPages = $totalCount > 0 ? (int)ceil($totalCount / $perPage) : 1;
        $page = min(max(1, $page), $totalPages);
        $offset = ($page - 1) * $perPage;

        if ($status !== '') {
            $quotes = Quote::findByStatus($status, $sort, $order, $perPage, $offset, $search !== '' ? $search : null);
        } else {
            $quotes = Quote::all($sort, $order, $perPage, $offset, $search !== '' ? $search : null);
        }

        if (self::isApiRequest()) {
            Response::success('Quotes retrieved', $quotes);
        } else {
            Response::view('quotes/index', [
                'quotes' => $quotes,
                'search' => $search,
                'status' => $status,
                'quoteStatuses' => Quote::getStatuses(),
                'sort' => $sort,
                'order' => $order,
                'page' => $page,
                'per_page' => $perPage,
                'total_count' => $totalCount,
                'total_pages' => $totalPages,
            ]);
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
            'questions' => $questions,
            'quoteStatuses' => Quote::getStatuses()
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

        if (!self::isApiRequest()) {
            $client = Client::find($quote['client_id'] ?? 0);
            $quote['client_name'] = $client ? trim(($client['first_name'] ?? '') . ' ' . ($client['last_name'] ?? '')) : 'N/A';
        }

        if (self::isApiRequest()) {
            Response::success('Quote retrieved', $quote);
        } else {
            Response::view('quotes/show', ['quote' => $quote, 'quoteStatuses' => Quote::getStatuses()]);
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
            'questions' => $questions,
            'quoteStatuses' => Quote::getStatuses()
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

        $validUntil = $_POST['valid_until'] ?? $quote['valid_until'];
        if ($validUntil === '' || $validUntil === null) {
            $validUntil = null;
        }

        $data = [
            'client_id' => $_POST['client_id'] ?? $quote['client_id'],
            'status' => $_POST['status'] ?? $quote['status'],
            'total_amount' => $_POST['total_amount'] ?? $quote['total_amount'],
            'currency' => $_POST['currency'] ?? $quote['currency'],
            'valid_until' => $validUntil,
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
            $indexUrl = Response::url('/quotes');
            $redirectTo = $_POST['redirect_to'] ?? '';
            if ($redirectTo !== '' && $redirectTo === $indexUrl) {
                // Path sin base para que Response::redirect() no duplique el base path
                Response::redirect('/quotes?updated=1');
            } else {
                Response::redirect('/quotes/' . $id);
            }
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
            Response::redirect('/quotes?deleted=1');
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