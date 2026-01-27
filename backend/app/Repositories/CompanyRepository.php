<?php
/**
 * H.A.C. Renovation - CompanyRepository
 */

class CompanyRepository extends BaseRepository
{
    protected $table = 'companies';

    /**
     * Obtener empresas activas ordenadas
     */
    public function findActive()
    {
        return $this->findAll(['is_active' => 1], 'name ASC');
    }
}
