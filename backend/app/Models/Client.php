<?php
/**
 * H.A.C. Renovation - Client Model
 */

class Client
{
    /**
     * Agregar atributo full_name a cliente(s)
     * @param array|array[]|false $data Cliente individual, array de clientes, o false si no se encuentra
     * @return array|array[]|false Cliente(s) con atributo full_name agregado, o false si no se encuentra
     */
    private static function addFullName($data)
    {
        // Si no hay datos o es false, retornar tal cual
        if (empty($data) || $data === false) {
            return $data;
        }

        // Si es un array de clientes (array multidimensional)
        if (isset($data[0]) && is_array($data[0])) {
            foreach ($data as &$client) {
                $client['full_name'] = trim(($client['first_name'] ?? '') . ' ' . ($client['last_name'] ?? ''));
            }
            return $data;
        }

        // Si es un cliente individual
        if (is_array($data)) {
            $data['full_name'] = trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? ''));
        }
        
        return $data;
    }

    /**
     * Agregar atributo full_address a cliente(s)
     * @param array|array[]|false $data Cliente individual, array de clientes, o false si no se encuentra
     * @return array|array[]|false Cliente(s) con atributo full_address agregado, o false si no se encuentra
     */
    private static function addFullAddress($data)
    {
        // Si no hay datos o es false, retornar tal cual
        if (empty($data) || $data === false) {
            return $data;
        }

        // Si es un array de clientes (array multidimensional)
        if (isset($data[0]) && is_array($data[0])) {
            foreach ($data as &$client) {
                $client['full_address'] = self::buildFullAddress($client);
            }
            return $data;
        }

        // Si es un cliente individual
        if (is_array($data)) {
            $data['full_address'] = self::buildFullAddress($data);
        }
        
        return $data;
    }

    /**
     * Construir dirección completa a partir de los campos del cliente
     * @param array $client Datos del cliente
     * @return string Dirección completa formateada
     */
    private static function buildFullAddress($client)
    {
        $parts = [];
        
        if (!empty($client['address'])) {
            $parts[] = trim($client['address']);
        }
        
        if (!empty($client['city'])) {
            $parts[] = trim($client['city']);
        }
        
        $stateZip = [];
        if (!empty($client['state'])) {
            $stateZip[] = trim($client['state']);
        }
        if (!empty($client['zip_code'])) {
            $stateZip[] = trim($client['zip_code']);
        }
        if (!empty($stateZip)) {
            $parts[] = implode(' ', $stateZip);
        }
        
        if (!empty($client['country'])) {
            $parts[] = trim($client['country']);
        }
        
        return implode(', ', $parts);
    }

    /**
     * Obtener todos los clientes
     */
    public static function all()
    {
        $repo = new ClientRepository();
        $clients = $repo->findAll([], 'created_at DESC');
        $clients = self::addFullName($clients);
        return self::addFullAddress($clients);
    }

    /**
     * Buscar por ID
     */
    public static function find($id)
    {
        $repo = new ClientRepository();
        $client = $repo->findById($id);
        if ($client) {
            $client = self::addFullName($client);
            $client = self::addFullAddress($client);
        }
        return $client;
    }

    /**
     * Buscar por email
     */
    public static function findByEmail($email)
    {
        $repo = new ClientRepository();
        $client = $repo->findByEmail($email);
        if ($client) {
            $client = self::addFullName($client);
            $client = self::addFullAddress($client);
        }
        return $client;
    }

    /**
     * Buscar con cotizaciones
     * @param int|null $limit
     * @param int $offset
     * @param string $orderBy name|phone|quotes|created_at
     * @param string $orderDir asc|desc
     */
    public static function withQuotes($limit = null, $offset = 0, $orderBy = 'created_at', $orderDir = 'desc')
    {
        $repo = new ClientRepository();
        $clients = $repo->findWithQuotes($limit, $offset, $orderBy, $orderDir);
        $clients = self::addFullName($clients);
        return self::addFullAddress($clients);
    }

    /**
     * Total de clientes
     */
    public static function count()
    {
        $repo = new ClientRepository();
        return $repo->getCount();
    }

    /**
     * Total de clientes que coinciden con búsqueda
     */
    public static function searchCount($term)
    {
        $repo = new ClientRepository();
        return $repo->getSearchCount($term);
    }

    /**
     * Buscar clientes
     */
    public static function search($term)
    {
        $repo = new ClientRepository();
        $clients = $repo->search($term);
        $clients = self::addFullName($clients);
        return self::addFullAddress($clients);
    }

    /**
     * Buscar clientes con cantidad de cotizaciones
     * @param int|null $limit
     * @param int $offset
     * @param string $orderBy name|phone|quotes|created_at
     * @param string $orderDir asc|desc
     */
    public static function searchWithQuotes($term, $limit = null, $offset = 0, $orderBy = 'created_at', $orderDir = 'desc')
    {
        $repo = new ClientRepository();
        $clients = $repo->searchWithQuotes($term, $limit, $offset, $orderBy, $orderDir);
        $clients = self::addFullName($clients);
        return self::addFullAddress($clients);
    }

    /**
     * Crear cliente
     */
    public static function create($data)
    {
        $repo = new ClientRepository();
        return $repo->create($data);
    }

    /**
     * Actualizar cliente
     */
    public static function update($id, $data)
    {
        $repo = new ClientRepository();
        return $repo->update($id, $data);
    }

    /**
     * Eliminar cliente
     */
    public static function delete($id)
    {
        $repo = new ClientRepository();
        return $repo->delete($id);
    }

    /**
     * Obtener nombre completo
     */
    public static function getFullName($client)
    {
        return trim(($client['first_name'] ?? '') . ' ' . ($client['last_name'] ?? ''));
    }
}