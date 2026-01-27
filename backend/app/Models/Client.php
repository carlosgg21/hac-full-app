<?php
/**
 * H.A.C. Renovation - Client Model
 */

class Client
{
    /**
     * Obtener todos los clientes
     */
    public static function all()
    {
        $repo = new ClientRepository();
        return $repo->findAll([], 'created_at DESC');
    }

    /**
     * Buscar por ID
     */
    public static function find($id)
    {
        $repo = new ClientRepository();
        return $repo->findById($id);
    }

    /**
     * Buscar por email
     */
    public static function findByEmail($email)
    {
        $repo = new ClientRepository();
        return $repo->findByEmail($email);
    }

    /**
     * Buscar con cotizaciones
     */
    public static function withQuotes($limit = null)
    {
        $repo = new ClientRepository();
        return $repo->findWithQuotes($limit);
    }

    /**
     * Buscar clientes
     */
    public static function search($term)
    {
        $repo = new ClientRepository();
        return $repo->search($term);
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