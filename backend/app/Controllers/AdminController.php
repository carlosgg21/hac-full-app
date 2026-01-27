<?php
/**
 * H.A.C. Renovation - AdminController
 */

class AdminController
{
    /**
     * Constructor - Requerir autenticación
     */
    public function __construct()
    {
        Auth::requireAuth();
    }

    /**
     * Dashboard principal
     */
    public function dashboard()
    {
        // Estadísticas
        $stats = [
            'total_clients' => (new ClientRepository())->count(),
            'total_quotes' => (new QuoteRepository())->count(),
            'pending_quotes' => (new QuoteRepository())->count(['status' => 'pending']),
            'total_projects' => (new ProjectRepository())->count(),
            'active_projects' => (new ProjectRepository())->count(['status' => 'in_progress'])
        ];

        // Cotizaciones recientes
        $recentQuotes = Quote::all();
        $recentQuotes = array_slice($recentQuotes, 0, 5);

        // Proyectos recientes
        $recentProjects = Project::all();
        $recentProjects = array_slice($recentProjects, 0, 5);

        Response::view('admin/dashboard', [
            'stats' => $stats,
            'recentQuotes' => $recentQuotes,
            'recentProjects' => $recentProjects
        ]);
    }
}