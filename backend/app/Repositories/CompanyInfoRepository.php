<?php
/**
 * H.A.C. Renovation - CompanyInfoRepository
 */

class CompanyInfoRepository extends BaseRepository
{
    protected $table = 'company_info';

    /**
     * Obtener información por company_id
     */
    public function findByCompanyId($companyId)
    {
        $sql = "SELECT * FROM `{$this->table}` WHERE `company_id` = :company_id LIMIT 1";
        return $this->db->fetchOne($sql, ['company_id' => $companyId]);
    }

    /**
     * Crear o actualizar información de compañía
     * Si existe, actualiza; si no, crea
     */
    public function upsert($companyId, $data)
    {
        $existing = $this->findByCompanyId($companyId);
        
        if ($existing) {
            return $this->update($existing['id'], $data);
        } else {
            $data['company_id'] = $companyId;
            return $this->create($data);
        }
    }

    /**
     * Crear información de compañía
     */
    public function create($data)
    {
        // Campos permitidos en company_info (excluyendo id, created_at, updated_at)
        $allowedFields = [
            'company_id',
            'years_experience',
            'total_projects',
            'total_clients',
            'client_satisfaction',
            'team_size',
            'service_areas',
            'awards_certifications',
            'featured_stats',
            'testimonials_count',
            'about_text',
            'mission',
            'vision',
            'values'
        ];

        // Filtrar solo campos permitidos
        $filteredData = [];
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $filteredData[$field] = $data[$field];
            }
        }

        // Convertir campos JSON si son arrays
        $jsonFields = ['service_areas', 'awards_certifications', 'featured_stats', 'values'];
        foreach ($jsonFields as $field) {
            if (isset($filteredData[$field]) && is_array($filteredData[$field])) {
                $filteredData[$field] = json_encode($filteredData[$field]);
            }
        }

        return parent::create($filteredData);
    }

    /**
     * Actualizar información de compañía
     */
    public function update($id, $data)
    {
        // Campos permitidos en company_info (excluyendo id, company_id, created_at, updated_at)
        $allowedFields = [
            'years_experience',
            'total_projects',
            'total_clients',
            'client_satisfaction',
            'team_size',
            'service_areas',
            'awards_certifications',
            'featured_stats',
            'testimonials_count',
            'about_text',
            'mission',
            'vision',
            'values'
        ];

        // Filtrar solo campos permitidos
        $filteredData = [];
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $filteredData[$field] = $data[$field];
            }
        }

        // Convertir campos JSON si son arrays
        $jsonFields = ['service_areas', 'awards_certifications', 'featured_stats', 'values'];
        foreach ($jsonFields as $field) {
            if (isset($filteredData[$field]) && is_array($filteredData[$field])) {
                $filteredData[$field] = json_encode($filteredData[$field]);
            }
        }

        return parent::update($id, $filteredData);
    }
}
