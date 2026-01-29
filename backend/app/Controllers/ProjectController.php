<?php
/**
 * H.A.C. Renovation - ProjectController
 */

class ProjectController
{
    /**
     * Constructor - Requerir autenticación
     */
    public function __construct()
    {
        Auth::requireAuth();
    }

    /**
     * Listar proyectos
     */
    public function index()
    {
        $status = $_GET['status'] ?? '';
        
        if ($status) {
            $projects = Project::findByStatus($status);
        } else {
            $projects = Project::all();
        }

        if (self::isApiRequest()) {
            Response::success('Projects retrieved', $projects);
        } else {
            Response::view('projects/index', ['projects' => $projects, 'status' => $status]);
        }
    }

    /**
     * Mostrar proyecto
     */
    public function show($id)
    {
        $project = Project::find($id);
        
        if (!$project) {
            Response::notFound('Project not found');
        }

        if (self::isApiRequest()) {
            Response::success('Project retrieved', $project);
        } else {
            Response::view('projects/show', ['project' => $project]);
        }
    }

    /**
     * Crear proyecto desde cotización
     */
    public function store()
    {
        $quoteId = $_POST['quote_id'] ?? 0;
        
        if (empty($quoteId)) {
            if (self::isApiRequest()) {
                Response::error('Quote ID is required');
            } else {
                $_SESSION['error'] = 'Quote ID is required';
                Response::redirect('/quotes');
            }
        }

        $data = [
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? '',
            'status' => $_POST['status'] ?? 'planning',
            'start_date' => $_POST['start_date'] ?? null,
            'end_date' => $_POST['end_date'] ?? null,
            'assigned_to' => $_POST['assigned_to'] ?? null
        ];

        try {
            $id = Project::createFromQuote($quoteId, $data);

            if (self::isApiRequest()) {
                Response::success('Project created', ['id' => $id], 201);
            } else {
                $_SESSION['success'] = 'Project created successfully';
                Response::redirect('/projects/' . $id);
            }
        } catch (Exception $e) {
            if (self::isApiRequest()) {
                Response::error($e->getMessage());
            } else {
                $_SESSION['error'] = $e->getMessage();
                Response::redirect('/quotes');
            }
        }
    }

    /**
     * Actualizar proyecto
     */
    public function update($id)
    {
        $project = Project::find($id);
        
        if (!$project) {
            Response::notFound('Project not found');
        }

        $data = [
            'name' => $_POST['name'] ?? $project['name'],
            'description' => $_POST['description'] ?? $project['description'],
            'status' => $_POST['status'] ?? $project['status'],
            'start_date' => $_POST['start_date'] ?? $project['start_date'],
            'end_date' => $_POST['end_date'] ?? $project['end_date'],
            'budget' => $_POST['budget'] ?? $project['budget'],
            'actual_cost' => $_POST['actual_cost'] ?? $project['actual_cost'],
            'progress' => $_POST['progress'] ?? $project['progress'],
            'notes' => $_POST['notes'] ?? $project['notes'],
            'assigned_to' => $_POST['assigned_to'] ?? $project['assigned_to']
        ];

        Project::update($id, $data);

        if (self::isApiRequest()) {
            Response::success('Project updated');
        } else {
            $_SESSION['success'] = 'Project updated successfully';
            Response::redirect('/projects/' . $id);
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