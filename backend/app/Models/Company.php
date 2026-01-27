<?php
/**
 * H.A.C. Renovation - Company Model
 */

class Company
{
    /**
     * Obtener todas las empresas
     */
    public static function all()
    {
        $repo = new CompanyRepository();
        return $repo->findAll([], 'name ASC');
    }

    /**
     * Obtener empresas activas
     */
    public static function active()
    {
        $repo = new CompanyRepository();
        return $repo->findActive();
    }

    /**
     * Buscar por ID
     */
    public static function find($id)
    {
        $repo = new CompanyRepository();
        return $repo->findById($id);
    }

    /**
     * Buscar por código
     */
    public static function findByCode($code)
    {
        $repo = new CompanyRepository();
        return $repo->findOne(['code' => $code]);
    }

    /**
     * Obtener la primera compañía del sistema (la única)
     * @return array|null Array asociativo con los datos de la compañía o null si no existe
     */
    public static function first()
    {
        $repo = new CompanyRepository();
        $companies = $repo->findAll([], 'id ASC', 1);
        return !empty($companies) ? $companies[0] : null;
    }

    /**
     * Crear empresa
     */
    public static function create($data)
    {
        $repo = new CompanyRepository();
        
        // Convertir social_media a JSON si es array
        if (isset($data['social_media']) && is_array($data['social_media'])) {
            $data['social_media'] = json_encode($data['social_media']);
        }
        
        return $repo->create($data);
    }

    /**
     * Actualizar empresa
     */
    public static function update($id, $data)
    {
        $repo = new CompanyRepository();
        
        // Convertir social_media a JSON si es array
        if (isset($data['social_media']) && is_array($data['social_media'])) {
            $data['social_media'] = json_encode($data['social_media']);
        }
        
        return $repo->update($id, $data);
    }

    /**
     * Eliminar empresa
     */
    public static function delete($id)
    {
        $repo = new CompanyRepository();
        return $repo->delete($id);
    }

    /**
     * Obtener social_media decodificado
     */
    public static function getSocialMedia($company)
    {
        if (empty($company['social_media'])) {
            return [
                'facebook' => '',
                'instagram' => '',
                'twitter' => '',
                'linkedin' => '',
                'youtube' => ''
            ];
        }

        $social = json_decode($company['social_media'], true);
        return $social ?: [
            'facebook' => '',
            'instagram' => '',
            'twitter' => '',
            'linkedin' => '',
            'youtube' => ''
        ];
    }

    /**
     * Obtener información de la compañía (company_info)
     */
    public static function getInfo($companyId)
    {
        $repo = new CompanyInfoRepository();
        return $repo->findByCompanyId($companyId);
    }

    /**
     * Actualizar o crear información de la compañía
     */
    public static function updateInfo($companyId, $data)
    {
        $repo = new CompanyInfoRepository();
        return $repo->upsert($companyId, $data);
    }

    /**
     * Obtener compañía con su información
     */
    public static function getInfoWithCompany($companyId)
    {
        $company = self::find($companyId);
        if (!$company) {
            return null;
        }

        $info = self::getInfo($companyId);
        $company['info'] = $info ?: [];
        
        return $company;
    }

    /**
     * Obtener campos JSON decodificados de company_info
     */
    public static function getInfoJsonFields($info)
    {
        if (!$info) {
            return [
                'service_areas' => [],
                'awards_certifications' => [],
                'featured_stats' => [],
                'values' => []
            ];
        }

        $jsonFields = ['service_areas', 'awards_certifications', 'featured_stats', 'values'];
        $decoded = [];

        foreach ($jsonFields as $field) {
            if (empty($info[$field])) {
                $decoded[$field] = [];
            } else {
                $decoded[$field] = json_decode($info[$field], true) ?: [];
            }
        }

        return $decoded;
    }

    /**
     * Formatear información de company_info para respuesta API
     * @param array|null $companyInfo Datos de company_info
     * @param array $infoJson Campos JSON decodificados
     * @return array Array formateado con toda la información
     */
    public static function formatCompanyInfo($companyInfo, $infoJson)
    {
        return [
            'years_experience' => (int)($companyInfo['years_experience'] ?? 0),
            'total_projects' => (int)($companyInfo['total_projects'] ?? 0),
            'total_clients' => (int)($companyInfo['total_clients'] ?? 0),
            'client_satisfaction' => (float)($companyInfo['client_satisfaction'] ?? 0.00),
            'team_size' => (int)($companyInfo['team_size'] ?? 0),
            'testimonials_count' => (int)($companyInfo['testimonials_count'] ?? 0),
            'service_areas' => $infoJson['service_areas'],
            'awards_certifications' => $infoJson['awards_certifications'],
            'featured_stats' => $infoJson['featured_stats'],
            'values' => $infoJson['values'],
            'about_text' => $companyInfo['about_text'] ?? '',
            'mission' => $companyInfo['mission'] ?? '',
            'vision' => $companyInfo['vision'] ?? ''
        ];
    }
}
