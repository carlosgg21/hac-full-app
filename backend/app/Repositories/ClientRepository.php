<?php
/**
 * H.A.C. Renovation - ClientRepository
 */

class ClientRepository extends BaseRepository
{
    protected $table = 'clients';

    /**
     * Buscar cliente por email
     */
    public function findByEmail($email)
    {
        return $this->findOne(['email' => $email]);
    }

    /**
     * Total de clientes (sin filtro búsqueda)
     */
    public function getCount()
    {
        $sql = "SELECT COUNT(*) as n FROM {$this->table}";
        $row = $this->db->fetchOne($sql);
        return (int)($row['n'] ?? 0);
    }

    /**
     * Total de clientes que coinciden con búsqueda
     */
    public function getSearchCount($term)
    {
        $sql = "SELECT COUNT(*) as n FROM {$this->table} 
                WHERE first_name LIKE :term 
                   OR last_name LIKE :term 
                   OR email LIKE :term 
                   OR phone LIKE :term";
        $row = $this->db->fetchOne($sql, ['term' => "%{$term}%"]);
        return (int)($row['n'] ?? 0);
    }

    /**
     * Buscar clientes con cotizaciones
     * @param int|null $limit
     * @param int $offset
     * @param string $orderBy name|phone|quotes
     * @param string $orderDir asc|desc
     */
    public function findWithQuotes($limit = null, $offset = 0, $orderBy = 'created_at', $orderDir = 'desc')
    {
        $dir = strtoupper($orderDir) === 'ASC' ? 'ASC' : 'DESC';
        $order = 'c.created_at DESC';
        if ($orderBy === 'name') {
            $order = "c.first_name {$dir}, c.last_name {$dir}";
        } elseif ($orderBy === 'phone') {
            $order = "c.phone {$dir}";
        } elseif ($orderBy === 'quotes') {
            $order = "quotes_count {$dir}";
        }

        $sql = "SELECT c.*, COUNT(q.id) as quotes_count 
                FROM {$this->table} c 
                LEFT JOIN quotes q ON c.id = q.client_id 
                GROUP BY c.id 
                ORDER BY {$order}";
        
        if ($limit !== null) {
            $sql .= " LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
        }

        return $this->db->fetchAll($sql);
    }

    /**
     * Buscar por nombre o email
     */
    public function search($term)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE first_name LIKE :term 
                   OR last_name LIKE :term 
                   OR email LIKE :term 
                   OR phone LIKE :term
                ORDER BY created_at DESC";
        
        $params = ['term' => "%{$term}%"];
        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Buscar clientes con cantidad de cotizaciones (para index con búsqueda)
     * @param int|null $limit
     * @param int $offset
     * @param string $orderBy name|phone|quotes
     * @param string $orderDir asc|desc
     */
    public function searchWithQuotes($term, $limit = null, $offset = 0, $orderBy = 'created_at', $orderDir = 'desc')
    {
        $dir = strtoupper($orderDir) === 'ASC' ? 'ASC' : 'DESC';
        $order = 'c.created_at DESC';
        if ($orderBy === 'name') {
            $order = "c.first_name {$dir}, c.last_name {$dir}";
        } elseif ($orderBy === 'phone') {
            $order = "c.phone {$dir}";
        } elseif ($orderBy === 'quotes') {
            $order = "quotes_count {$dir}";
        }

        $sql = "SELECT c.*, COUNT(q.id) as quotes_count 
                FROM {$this->table} c 
                LEFT JOIN quotes q ON c.id = q.client_id 
                WHERE c.first_name LIKE :term 
                   OR c.last_name LIKE :term 
                   OR c.email LIKE :term 
                   OR c.phone LIKE :term
                GROUP BY c.id 
                ORDER BY {$order}";
        
        if ($limit !== null) {
            $sql .= " LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
        }

        $params = ['term' => "%{$term}%"];
        return $this->db->fetchAll($sql, $params);
    }
}