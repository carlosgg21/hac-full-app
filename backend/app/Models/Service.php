<?php
/**
 * H.A.C. Renovation - Service Model
 */

class Service
{
    /**
     * Obtener todos los servicios
     */
    public static function all()
    {
        $repo = new ServiceRepository();
        return $repo->findAll([], 'name ASC');
    }

    /**
     * Obtener servicios activos
     */
    public static function active()
    {
        $repo = new ServiceRepository();
        return $repo->findActive();
    }

    /**
     * Buscar por ID
     */
    public static function find($id)
    {
        $repo = new ServiceRepository();
        return $repo->findById($id);
    }

    /**
     * Crear servicio
     */
    public static function create($data)
    {
        $repo = new ServiceRepository();
        return $repo->create($data);
    }

    /**
     * Actualizar servicio
     */
    public static function update($id, $data)
    {
        $repo = new ServiceRepository();
        return $repo->update($id, $data);
    }

    /**
     * Eliminar servicio
     */
    public static function delete($id)
    {
        $repo = new ServiceRepository();
        return $repo->delete($id);
    }
}
