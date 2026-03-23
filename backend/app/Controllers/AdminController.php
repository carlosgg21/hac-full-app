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
        $db = Database::getInstance();

        $quoteRepo = new QuoteRepository();

        // Estadísticas
        $stats = [
            'total_clients' => (new ClientRepository())->count(),
            'total_quotes' => $quoteRepo->count(),
            'pending_quotes' => $quoteRepo->count(['status' => 'pending']),
            'accepted_quotes' => $quoteRepo->count(['status' => 'accepted']),
            'rejected_quotes' => $quoteRepo->count(['status' => 'rejected']),
        ];

        // Cotizaciones por tipo de servicio (del campo JSON metadata)
        $serviceStats = $db->fetchAll(
            "SELECT JSON_UNQUOTE(JSON_EXTRACT(metadata, '$.service_type')) as service_type, COUNT(*) as total
             FROM quotes
             WHERE metadata IS NOT NULL AND JSON_EXTRACT(metadata, '$.service_type') IS NOT NULL
             GROUP BY service_type
             ORDER BY total DESC"
        );

        // Clientes por mes (año actual)
        $clientsByMonth = $db->fetchAll(
            "SELECT MONTH(created_at) as month, COUNT(*) as total
             FROM clients
             WHERE YEAR(created_at) = YEAR(CURDATE())
             GROUP BY MONTH(created_at)
             ORDER BY month"
        );

        // Cotizaciones recientes
        $recentQuotes = $quoteRepo->findWithClient([], 5, 0, 'q.created_at', 'desc');

        Response::view('admin/dashboard', [
            'stats' => $stats,
            'serviceStats' => $serviceStats,
            'clientsByMonth' => $clientsByMonth,
            'recentQuotes' => $recentQuotes,
        ]);
    }
}