<?php
/**
 * H.A.C. Renovation - ProjectRepository
 */

class ProjectRepository extends BaseRepository
{
    protected $table = 'projects';

    /**
     * Buscar por número de proyecto
     */
    public function findByProjectNumber($projectNumber)
    {
        return $this->findOne(['project_number' => $projectNumber]);
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
     * Obtener proyectos con información completa
     */
    public function findWithDetails($conditions = [], $limit = null)
    {
        $sql = "SELECT p.*, 
                       q.quote_number,
                       c.first_name, 
                       c.last_name, 
                       c.email as client_email,
                       CONCAT(c.first_name, ' ', c.last_name) as client_name,
                       u.username as assigned_user
                FROM {$this->table} p
                INNER JOIN quotes q ON p.quote_id = q.id
                INNER JOIN clients c ON p.client_id = c.id
                LEFT JOIN users u ON p.assigned_to = u.id";
        
        $params = [];
        $where = [];

        if (!empty($conditions)) {
            foreach ($conditions as $field => $value) {
                if (strpos($field, '.') !== false) {
                    $where[] = "{$field} = :{$field}";
                } else {
                    $where[] = "p.{$field} = :{$field}";
                }
                $params[$field] = $value;
            }
        }

        if (!empty($where)) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        $sql .= " ORDER BY p.created_at DESC";

        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }

        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Generar número de proyecto único
     */
    public function generateProjectNumber()
    {
        $date = date('Ymd');
        $counter = 1;
        
        do {
            $projectNumber = sprintf('PRJ-%s-%04d', $date, $counter);
            $exists = $this->findByProjectNumber($projectNumber);
            $counter++;
        } while ($exists);

        return $projectNumber;
    }
}