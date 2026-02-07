<?php
/**
 * H.A.C. Renovation - QuoteRepository
 */

class QuoteRepository extends BaseRepository
{
    protected $table = 'quotes';

    /**
     * Buscar por número de cotización
     */
    public function findByQuoteNumber($quoteNumber)
    {
        return $this->findOne(['quote_number' => $quoteNumber]);
    }

    /**
     * Buscar por cliente
     */
    public function findByClient($clientId)
    {
        return $this->findAll(['client_id' => $clientId], 'created_at DESC');
    }

    /**
     * Buscar por estado
     */
    public function findByStatus($status)
    {
        return $this->findAll(['status' => $status], 'created_at DESC');
    }

    /** Columnas permitidas para ordenar (campo => expresión SQL) */
    private static $sortColumns = [
        'quote_number' => 'q.quote_number',
        'client_name'  => 'client_name',
        'status'       => 'q.status',
        'total_amount' => 'q.total_amount',
        'created_at'   => 'q.created_at',
    ];

    /**
     * Total de cotizaciones (opcional filtro por status y búsqueda)
     */
    public function getCount($conditions = [], $searchTerm = null)
    {
        $sql = "SELECT COUNT(*) as n FROM {$this->table} q INNER JOIN clients c ON q.client_id = c.id";
        $params = [];
        $where = [];

        foreach ($conditions as $field => $value) {
            $where[] = strpos($field, '.') !== false ? "{$field} = :{$field}" : "q.{$field} = :{$field}";
            $params[$field] = $value;
        }
        if ($searchTerm !== null && $searchTerm !== '') {
            $where[] = "(q.quote_number LIKE :search_term_1 OR CONCAT(c.first_name, ' ', c.last_name) LIKE :search_term_2)";
            $params['search_term_1'] = '%' . $searchTerm . '%';
            $params['search_term_2'] = '%' . $searchTerm . '%';
        }
        if (!empty($where)) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }
        $row = $this->db->fetchOne($sql, $params);
        return (int)($row['n'] ?? 0);
    }

    /**
     * Obtener cotizaciones con información del cliente
     *
     * @param array $conditions
     * @param int|null $limit
     * @param int $offset
     * @param string|null $orderBy Campo permitido: quote_number, client_name, status, total_amount, created_at
     * @param string $orderDir 'asc' o 'desc'
     * @param string|null $searchTerm Búsqueda por quote_number o nombre de cliente
     */
    public function findWithClient($conditions = [], $limit = null, $offset = 0, $orderBy = null, $orderDir = 'desc', $searchTerm = null)
    {
        $sql = "SELECT q.*, 
                       c.id as client_id, 
                       c.first_name, 
                       c.last_name, 
                       c.email as client_email, 
                       c.phone as client_phone,
                       CONCAT(c.first_name, ' ', c.last_name) as client_name
                FROM {$this->table} q
                INNER JOIN clients c ON q.client_id = c.id";
        
        $params = [];
        $where = [];

        if (!empty($conditions)) {
            foreach ($conditions as $field => $value) {
                if (strpos($field, '.') !== false) {
                    $where[] = "{$field} = :{$field}";
                } else {
                    $where[] = "q.{$field} = :{$field}";
                }
                $params[$field] = $value;
            }
        }
        if ($searchTerm !== null && $searchTerm !== '') {
            $where[] = "(q.quote_number LIKE :search_term_1 OR CONCAT(c.first_name, ' ', c.last_name) LIKE :search_term_2)";
            $params['search_term_1'] = '%' . $searchTerm . '%';
            $params['search_term_2'] = '%' . $searchTerm . '%';
        }

        if (!empty($where)) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        $orderDir = strtolower($orderDir) === 'asc' ? 'ASC' : 'DESC';
        if ($orderBy !== null && isset(self::$sortColumns[$orderBy])) {
            $sql .= " ORDER BY " . self::$sortColumns[$orderBy] . " " . $orderDir;
        } else {
            $sql .= " ORDER BY q.created_at DESC";
        }

        if ($limit !== null) {
            $sql .= " LIMIT " . (int) $limit . " OFFSET " . (int) $offset;
        }

        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Generar número de cotización único
     */
    public function generateQuoteNumber()
    {
        $date = date('Ymd');
        $counter = 1;
        
        do {
            $quoteNumber = sprintf('QT-%s-%04d', $date, $counter);
            $exists = $this->findByQuoteNumber($quoteNumber);
            $counter++;
        } while ($exists);

        return $quoteNumber;
    }

    /**
     * Obtener respuestas de una cotización
     */
    public function getAnswers($quoteId)
    {
        $sql = "SELECT qa.*, q.question_text, q.question_type
                FROM quote_answers qa
                INNER JOIN questions q ON qa.question_id = q.id
                WHERE qa.quote_id = :quote_id
                ORDER BY q.order ASC";
        
        return $this->db->fetchAll($sql, ['quote_id' => $quoteId]);
    }
}