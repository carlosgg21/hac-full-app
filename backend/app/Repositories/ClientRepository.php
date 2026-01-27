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
     * Buscar clientes con cotizaciones
     */
    public function findWithQuotes($limit = null)
    {
        $sql = "SELECT c.*, COUNT(q.id) as quotes_count 
                FROM {$this->table} c 
                LEFT JOIN quotes q ON c.id = q.client_id 
                GROUP BY c.id 
                ORDER BY c.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
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
}