<?php
/**
 * H.A.C. Renovation - Project Model
 */

class Project
{
    /**
     * Obtener todos los proyectos
     */
    public static function all()
    {
        $repo = new ProjectRepository();
        return $repo->findWithDetails();
    }

    /**
     * Buscar por ID
     */
    public static function find($id)
    {
        $repo = new ProjectRepository();
        return $repo->findById($id);
    }

    /**
     * Buscar por número de proyecto
     */
    public static function findByProjectNumber($projectNumber)
    {
        $repo = new ProjectRepository();
        return $repo->findByProjectNumber($projectNumber);
    }

    /**
     * Buscar por cliente
     */
    public static function findByClient($clientId)
    {
        $repo = new ProjectRepository();
        return $repo->findByClient($clientId);
    }

    /**
     * Buscar por estado
     */
    public static function findByStatus($status)
    {
        $repo = new ProjectRepository();
        return $repo->findByStatus($status);
    }

    /**
     * Crear proyecto desde cotización
     */
    public static function createFromQuote($quoteId, $data = [])
    {
        $quote = Quote::find($quoteId);
        
        if (!$quote) {
            throw new Exception('Quote not found');
        }

        $repo = new ProjectRepository();
        
        // Generar número de proyecto
        $projectNumber = $repo->generateProjectNumber();
        
        $projectData = array_merge([
            'quote_id' => $quoteId,
            'client_id' => $quote['client_id'],
            'project_number' => $projectNumber,
            'name' => 'Project ' . $quote['quote_number'],
            'status' => 'planning',
            'budget' => $quote['total_amount']
        ], $data);
        
        return $repo->create($projectData);
    }

    /**
     * Crear proyecto
     */
    public static function create($data)
    {
        $repo = new ProjectRepository();
        
        // Generar número de proyecto si no existe
        if (empty($data['project_number'])) {
            $data['project_number'] = $repo->generateProjectNumber();
        }
        
        return $repo->create($data);
    }

    /**
     * Actualizar proyecto
     */
    public static function update($id, $data)
    {
        $repo = new ProjectRepository();
        return $repo->update($id, $data);
    }

    /**
     * Eliminar proyecto
     */
    public static function delete($id)
    {
        $repo = new ProjectRepository();
        return $repo->delete($id);
    }
}