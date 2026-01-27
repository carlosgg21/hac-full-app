<?php
/**
 * H.A.C. Renovation - ReportController
 */

class ReportController
{
    /**
     * Constructor - Requerir autenticación
     */
    public function __construct()
    {
        Auth::requireAuth();
    }

    /**
     * Página principal de reportes
     */
    public function index()
    {
        Response::view('reports/index');
    }

    /**
     * Reporte de cotizaciones
     */
    public function quotes()
    {
        $startDate = $_GET['start_date'] ?? null;
        $endDate = $_GET['end_date'] ?? null;
        $status = $_GET['status'] ?? null;

        $conditions = [];
        if ($startDate) {
            $conditions['q.created_at >='] = $startDate;
        }
        if ($endDate) {
            $conditions['q.created_at <='] = $endDate . ' 23:59:59';
        }
        if ($status) {
            $conditions['q.status'] = $status;
        }

        $repo = new QuoteRepository();
        $quotes = $repo->findWithClient($conditions);

        // Estadísticas
        $stats = [
            'total' => count($quotes),
            'total_amount' => array_sum(array_column($quotes, 'total_amount')),
            'by_status' => []
        ];

        foreach ($quotes as $quote) {
            $status = $quote['status'];
            if (!isset($stats['by_status'][$status])) {
                $stats['by_status'][$status] = 0;
            }
            $stats['by_status'][$status]++;
        }

        if (self::isApiRequest()) {
            Response::success('Reporte de cotizaciones', [
                'quotes' => $quotes,
                'stats' => $stats
            ]);
        } else {
            Response::view('reports/quotes', [
                'quotes' => $quotes,
                'stats' => $stats,
                'filters' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'status' => $status
                ]
            ]);
        }
    }

    /**
     * Reporte de proyectos
     */
    public function projects()
    {
        $startDate = $_GET['start_date'] ?? null;
        $endDate = $_GET['end_date'] ?? null;
        $status = $_GET['status'] ?? null;

        $conditions = [];
        if ($startDate) {
            $conditions['p.created_at >='] = $startDate;
        }
        if ($endDate) {
            $conditions['p.created_at <='] = $endDate . ' 23:59:59';
        }
        if ($status) {
            $conditions['p.status'] = $status;
        }

        $repo = new ProjectRepository();
        $projects = $repo->findWithDetails($conditions);

        // Estadísticas
        $stats = [
            'total' => count($projects),
            'total_budget' => array_sum(array_filter(array_column($projects, 'budget'))),
            'total_cost' => array_sum(array_filter(array_column($projects, 'actual_cost'))),
            'by_status' => []
        ];

        foreach ($projects as $project) {
            $status = $project['status'];
            if (!isset($stats['by_status'][$status])) {
                $stats['by_status'][$status] = 0;
            }
            $stats['by_status'][$status]++;
        }

        if (self::isApiRequest()) {
            Response::success('Reporte de proyectos', [
                'projects' => $projects,
                'stats' => $stats
            ]);
        } else {
            Response::view('reports/projects', [
                'projects' => $projects,
                'stats' => $stats,
                'filters' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'status' => $status
                ]
            ]);
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