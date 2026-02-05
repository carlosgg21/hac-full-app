<?php
/**
 * H.A.C. Renovation - Quote Model
 */

class Quote
{
    /**
     * Obtener todas las cotizaciones
     */
    public static function all()
    {
        $repo = new QuoteRepository();
        return $repo->findWithClient();
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
     */
    public static function findByStatus($status)
    {
        $repo = new QuoteRepository();
        return $repo->findByStatus($status);
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