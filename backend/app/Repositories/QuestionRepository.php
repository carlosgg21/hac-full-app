<?php
/**
 * H.A.C. Renovation - QuestionRepository
 */

class QuestionRepository extends BaseRepository
{
    protected $table = 'questions';

    /**
     * Obtener preguntas activas ordenadas con información del servicio
     */
    public function findActive()
    {
        $sql = "SELECT q.*, s.name as service_name 
                FROM {$this->table} q 
                INNER JOIN services s ON q.service_id = s.id 
                WHERE q.is_active = 1 
                ORDER BY q.`order` ASC, q.id ASC";
        return $this->db->fetchAll($sql);
    }

    /**
     * Obtener todas las preguntas con información del servicio
     */
    public function findAll($conditions = [], $orderBy = null, $limit = null)
    {
        $sql = "SELECT q.*, s.name as service_name 
                FROM {$this->table} q 
                INNER JOIN services s ON q.service_id = s.id";
        $params = [];

        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $field => $value) {
                // Remover prefijo 'q.' si existe para el nombre del parámetro
                $paramName = str_replace('q.', '', $field);
                $fieldName = strpos($field, 'q.') === 0 ? $field : "q.{$field}";
                $where[] = "{$fieldName} = :{$paramName}";
                $params[$paramName] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        } else {
            $sql .= " ORDER BY q.`order` ASC, q.id ASC";
        }

        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }

        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Obtener pregunta por ID con información del servicio
     */
    public function findById($id)
    {
        $sql = "SELECT q.*, s.name as service_name 
                FROM {$this->table} q 
                INNER JOIN services s ON q.service_id = s.id 
                WHERE q.id = :id";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }

    /**
     * Obtener preguntas por service_id
     */
    public function findByServiceId($serviceId)
    {
        $sql = "SELECT q.*, s.name as service_name 
                FROM {$this->table} q 
                INNER JOIN services s ON q.service_id = s.id 
                WHERE q.is_active = 1 AND q.service_id = :service_id 
                ORDER BY q.`order` ASC, q.id ASC";
        return $this->db->fetchAll($sql, ['service_id' => $serviceId]);
    }

    /**
     * Buscar preguntas con filtros, búsqueda y orden
     */
    public function findFiltered($serviceId = null, $search = '', $orderByCol = 'q.id', $orderDir = 'desc')
    {
        $sql = "SELECT q.*, s.name as service_name
                FROM {$this->table} q
                INNER JOIN services s ON q.service_id = s.id";
        $params = [];
        $where = [];

        if ($serviceId) {
            $where[] = "q.service_id = :service_id";
            $params['service_id'] = $serviceId;
        }
        if ($search !== '') {
            $where[] = "q.question_text LIKE :search";
            $params['search'] = '%' . $search . '%';
        }
        if (!empty($where)) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        $dir = strtoupper($orderDir) === 'ASC' ? 'ASC' : 'DESC';
        $sql .= " ORDER BY {$orderByCol} {$dir}";

        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Reordenar preguntas
     */
    public function reorder($questionIds)
    {
        $this->db->beginTransaction();
        
        try {
            foreach ($questionIds as $order => $questionId) {
                $this->update($questionId, ['order' => $order + 1]);
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }
}