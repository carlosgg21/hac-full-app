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

    /**
     * Obtener cotizaciones con información del cliente
     */
    public function findWithClient($conditions = [], $limit = null)
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

        if (!empty($where)) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        $sql .= " ORDER BY q.created_at DESC";

        if ($limit) {
            $sql .= " LIMIT {$limit}";
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