<?php
/**
 * H.A.C. Renovation - Question Model
 */

class Question
{
    /**
     * Obtener todas las preguntas
     */
    public static function all()
    {
        $repo = new QuestionRepository();
        return $repo->findAll();
    }

    /**
     * Obtener preguntas activas
     */
    public static function active()
    {
        $repo = new QuestionRepository();
        return $repo->findActive();
    }

    /**
     * Buscar por ID
     */
    public static function find($id)
    {
        $repo = new QuestionRepository();
        return $repo->findById($id);
    }

    /**
     * Buscar por service_id
     */
    public static function findByServiceId($serviceId)
    {
        $repo = new QuestionRepository();
        return $repo->findByServiceId($serviceId);
    }

    /**
     * Crear pregunta
     */
    public static function create($data)
    {
        $repo = new QuestionRepository();
        
        // Si no tiene orden, asignar el último + 1
        if (!isset($data['order'])) {
            $all = $repo->findAll([], 'q.`order` DESC', 1);
            $data['order'] = $all ? (int)$all[0]['order'] + 1 : 1;
        }
        
        // Convertir opciones a JSON si es array
        if (isset($data['options']) && is_array($data['options'])) {
            $data['options'] = json_encode($data['options']);
        }
        
        // Convertir translations a JSON si es array
        if (isset($data['translations']) && is_array($data['translations'])) {
            $data['translations'] = json_encode($data['translations']);
        }
        
        return $repo->create($data);
    }

    /**
     * Actualizar pregunta
     */
    public static function update($id, $data)
    {
        $repo = new QuestionRepository();
        
        // Convertir opciones a JSON si es array
        if (isset($data['options']) && is_array($data['options'])) {
            $data['options'] = json_encode($data['options']);
        }
        
        // Convertir translations a JSON si es array
        if (isset($data['translations']) && is_array($data['translations'])) {
            $data['translations'] = json_encode($data['translations']);
        }
        
        return $repo->update($id, $data);
    }

    /**
     * Eliminar pregunta
     */
    public static function delete($id)
    {
        $repo = new QuestionRepository();
        return $repo->delete($id);
    }

    /**
     * Reordenar preguntas
     */
    public static function reorder($questionIds)
    {
        $repo = new QuestionRepository();
        return $repo->reorder($questionIds);
    }

    /**
     * Obtener opciones decodificadas
     */
    public static function getOptions($question)
    {
        if (empty($question['options'])) {
            return [];
        }

        $options = json_decode($question['options'], true);
        return $options ?: [];
    }

    /**
     * Obtener traducciones decodificadas
     */
    public static function getTranslations($question)
    {
        if (empty($question['translations'])) {
            return ['fr' => '', 'es' => ''];
        }

        $translations = json_decode($question['translations'], true);
        return $translations ?: ['fr' => '', 'es' => ''];
    }
}