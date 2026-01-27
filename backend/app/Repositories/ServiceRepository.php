<?php
/**
 * H.A.C. Renovation - ServiceRepository
 */

class ServiceRepository extends BaseRepository
{
    protected $table = 'services';

    /**
     * Obtener servicios activos ordenados
     */
    public function findActive()
    {
        return $this->findAll(['is_active' => 1], 'name ASC');
    }
}
