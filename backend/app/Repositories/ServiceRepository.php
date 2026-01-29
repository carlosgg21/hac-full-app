<?php
/**
 * H.A.C. Renovation - ServiceRepository
 */

class ServiceRepository extends BaseRepository
{
    protected $table = 'services';

    /** Columnas permitidas para SELECT y ORDER BY */
    protected $allowedColumns = ['id', 'name', 'description', 'is_active', 'created_at', 'updated_at'];

    /**
     * Obtener servicios activos
     * @param array|null $fields Columnas a devolver (ej: ['id', 'name', 'description']). Si null o vacío, devuelve todas
     * @param string|null $orderBy Orden (ej: 'name ASC', 'created_at DESC'). Si null o vacío, orden por name ASC
     * @return array
     */
    public function findActive(array $fields = null, $orderBy = null)
    {
        $select = '*';
        if ($fields !== null && !empty($fields)) {
            $safe = array_intersect($fields, $this->allowedColumns);
            if (!empty($safe)) {
                $select = implode(', ', array_map(function ($c) {
                    return "`{$c}`";
                }, $safe));
            }
        }

        $order = 'name ASC';
        if ($orderBy !== null && $orderBy !== '') {
            $parts = preg_split('/\s+/', trim($orderBy), 2);
            $col = $parts[0];
            if (in_array($col, $this->allowedColumns, true)) {
                $dir = (isset($parts[1]) && strtoupper($parts[1]) === 'DESC') ? 'DESC' : 'ASC';
                $order = "`{$col}` {$dir}";
            }
        }

        $sql = "SELECT {$select} FROM {$this->table} WHERE is_active = 1 ORDER BY {$order}";
        return $this->db->fetchAll($sql, []);
    }
}
