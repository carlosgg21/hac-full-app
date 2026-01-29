<?php
/**
 * H.A.C. Renovation - Service Model
 */

class Service
{
    /** @var ServiceRepository|null */
    private static $repo;

    /**
     * Instancia del repositorio (evita repetir new ServiceRepository en cada método)
     */
    private static function repo()
    {
        if (self::$repo === null) {
            self::$repo = new ServiceRepository();
        }
        return self::$repo;
    }

    /**
     * Obtener todos los servicios
     */
    public static function all()
    {
        return self::repo()->findAll([], 'name ASC');
    }

    /**
     * Obtener servicios activos
     * @param array|null $fields Columnas a devolver (ej: ['id', 'name']). null = todas
     * @param string|null $orderBy Orden (ej: 'name ASC'). null = name ASC
     */
    public static function active(array $fields = null, $orderBy = null)
    {
        return self::repo()->findActive($fields, $orderBy);
    }

    /**
     * Buscar por ID
     */
    public static function find($id)
    {
        return self::repo()->findById($id);
    }

    /**
     * Crear servicio
     */
    public static function create($data)
    {
        return self::repo()->create($data);
    }

    /**
     * Actualizar servicio
     */
    public static function update($id, $data)
    {
        return self::repo()->update($id, $data);
    }

    /**
     * Eliminar servicio
     */
    public static function delete($id)
    {
        return self::repo()->delete($id);
    }
}
