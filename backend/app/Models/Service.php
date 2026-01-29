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
     * @param array|null $fields Columnas a devolver (ej: ['id', 'name']). null = todas
     * @param string|null $orderBy Orden (ej: 'name ASC', 'created_at DESC'). null = name ASC
     */
    public static function active(array $fields = null, $orderBy = null)
    {
        $repo = new ServiceRepository();
        return $repo->findActive($fields, $orderBy);
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
