<?php
/**
 * H.A.C. Renovation - BaseRepository
 * Clase base para repositorios
 */

class BaseRepository
{
    protected $db;
    protected $table;
    protected $primaryKey = 'id';

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Obtener todos los registros
     */
    public function findAll($conditions = [], $orderBy = null, $limit = null)
    {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];

        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $field => $value) {
                $where[] = "{$field} = :{$field}";
                $params[$field] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }

        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }

        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Obtener un registro por ID
     */
    public function findById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }

    /**
     * Obtener un registro por condición
     */
    public function findOne($conditions)
    {
        $where = [];
        $params = [];

        foreach ($conditions as $field => $value) {
            $where[] = "{$field} = :{$field}";
            $params[$field] = $value;
        }

        $sql = "SELECT * FROM {$this->table} WHERE " . implode(' AND ', $where) . " LIMIT 1";
        return $this->db->fetchOne($sql, $params);
    }

    /**
     * Crear nuevo registro
     */
    public function create($data)
    {
        return $this->db->insert($this->table, $data);
    }

    /**
     * Actualizar registro
     */
    public function update($id, $data)
    {
        return $this->db->update($this->table, $data, "{$this->primaryKey} = :id", ['id' => $id]);
    }

    /**
     * Eliminar registro
     */
    public function delete($id)
    {
        return $this->db->delete($this->table, "{$this->primaryKey} = :id", ['id' => $id]);
    }

    /**
     * Contar registros
     */
    public function count($conditions = [])
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $params = [];

        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $field => $value) {
                $where[] = "{$field} = :{$field}";
                $params[$field] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        $result = $this->db->fetchOne($sql, $params);
        return (int)$result['total'];
    }

    /**
     * Verificar existencia
     */
    public function exists($id)
    {
        $result = $this->findById($id);
        return $result !== false;
    }
}