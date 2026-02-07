<?php
/**
 * H.A.C. Renovation - Quote Model
 */

class Quote
{
    /**
     * Definición de estados de cotización (label, class CSS, icon) para uso en vistas
     *
     * @return array<string, array{label: string, class: string, icon: string}>
     */
    public static function getStatuses()
    {
        return [
            'draft'    => ['label' => 'Draft',    'class' => 'bg-gray-100 text-gray-700',   'icon' => 'bi-file-earmark'],
            'pending'  => ['label' => 'Pending',  'class' => 'bg-yellow-100 text-yellow-700', 'icon' => 'bi-clock'],
            'sent'     => ['label' => 'Sent',     'class' => 'bg-green-100 text-green-700',  'icon' => 'bi-send'],
            'accepted' => ['label' => 'Accepted', 'class' => 'bg-green-100 text-green-700', 'icon' => 'bi-check-circle'],
            'rejected' => ['label' => 'Rejected', 'class' => 'bg-red-100 text-red-700',     'icon' => 'bi-x-circle'],
            'expired'  => ['label' => 'Expired',  'class' => 'bg-red-100 text-red-600',     'icon' => 'bi-calendar-x'],
        ];
    }

    /**
     * Obtener la config de un estado (label, class, icon). Fallback a draft si no existe.
     *
     * @param string $status
     * @return array{label: string, class: string, icon: string}
     */
    public static function getStatusConfig($status)
    {
        $statuses = self::getStatuses();
        $status = strtolower($status ?? '');
        return $statuses[$status] ?? $statuses['draft'];
    }

    /**
     * Total de cotizaciones (opcional filtro por status y búsqueda)
     */
    public static function count($status = null, $search = null)
    {
        $repo = new QuoteRepository();
        $conditions = $status !== null && $status !== '' ? ['status' => $status] : [];
        return $repo->getCount($conditions, $search);
    }

    /**
     * Obtener todas las cotizaciones
     *
     * @param string|null $orderBy quote_number, client_name, status, total_amount, created_at
     * @param string $orderDir asc|desc
     * @param int|null $limit
     * @param int $offset
     * @param string|null $search Búsqueda por número o cliente
     */
    public static function all($orderBy = null, $orderDir = 'desc', $limit = null, $offset = 0, $search = null)
    {
        $repo = new QuoteRepository();
        return $repo->findWithClient([], $limit, $offset, $orderBy, $orderDir, $search);
    }

    /**
     * Buscar por ID
     */
    public static function find($id)
    {
        $repo = new QuoteRepository();
        $quote = $repo->findById($id);
        
        if ($quote) {
            $quote['answers'] = $repo->getAnswers($id);
        }
        
        return $quote;
    }

    /**
     * Buscar por número de cotización
     */
    public static function findByQuoteNumber($quoteNumber)
    {
        $repo = new QuoteRepository();
        return $repo->findByQuoteNumber($quoteNumber);
    }

    /**
     * Buscar por cliente
     */
    public static function findByClient($clientId)
    {
        $repo = new QuoteRepository();
        return $repo->findByClient($clientId);
    }

    /**
     * Buscar por estado
     *
     * @param string|null $orderBy quote_number, client_name, status, total_amount, created_at
     * @param string $orderDir asc|desc
     * @param int|null $limit
     * @param int $offset
     * @param string|null $search Búsqueda por número o cliente
     */
    public static function findByStatus($status, $orderBy = null, $orderDir = 'desc', $limit = null, $offset = 0, $search = null)
    {
        $repo = new QuoteRepository();
        return $repo->findWithClient(['status' => $status], $limit, $offset, $orderBy, $orderDir, $search);
    }

    /**
     * Crear cotización
     */
    public static function create($data)
    {
        $repo = new QuoteRepository();
        
        // Generar número de cotización si no existe
        if (empty($data['quote_number'])) {
            $data['quote_number'] = $repo->generateQuoteNumber();
        }
        
        // Establecer usuario creador si está autenticado
        if (Auth::check() && !isset($data['created_by'])) {
            $data['created_by'] = Auth::id();
        }
        
        $answers = $data['answers'] ?? null;
        unset($data['answers']);
        $quoteId = $repo->create($data);
        
        // Guardar respuestas si existen
        if ($answers !== null && is_array($answers)) {
            self::saveAnswers($quoteId, $answers);
        }
        
        return $quoteId;
    }

    /**
     * Actualizar cotización
     */
    public static function update($id, $data)
    {
        $repo = new QuoteRepository();
        
        // Guardar respuestas si existen
        if (isset($data['answers']) && is_array($data['answers'])) {
            self::saveAnswers($id, $data['answers']);
            unset($data['answers']);
        }
        
        return $repo->update($id, $data);
    }

    /**
     * Eliminar cotización
     */
    public static function delete($id)
    {
        $repo = new QuoteRepository();
        return $repo->delete($id);
    }

    /**
     * Guardar respuestas de la cotización
     */
    private static function saveAnswers($quoteId, $answers)
    {
        $db = Database::getInstance();
        
        // Eliminar respuestas existentes
        $db->delete('quote_answers', 'quote_id = :quote_id', ['quote_id' => $quoteId]);
        
        // Insertar nuevas respuestas
        foreach ($answers as $questionId => $answer) {
            $answerData = [
                'quote_id' => $quoteId,
                'question_id' => $questionId
            ];
            
            // Manejar diferentes tipos de respuestas
            if (is_array($answer)) {
                $answerData['answer_json'] = json_encode($answer);
            } elseif (is_numeric($answer)) {
                $answerData['answer_value'] = $answer;
            } else {
                $answerData['answer_text'] = $answer;
            }
            
            $db->insert('quote_answers', $answerData);
        }
    }

    /**
     * Obtener respuestas
     */
    public static function getAnswers($quoteId)
    {
        $repo = new QuoteRepository();
        return $repo->getAnswers($quoteId);
    }
}