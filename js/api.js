/**
 * API Client for H.A.C. Renovation Backend
 * Maneja todas las consultas a la API
 */

const API = {
    // URL base de la API (relativa)
    baseURL: '/backend/api',
    
    /**
     * Realizar petición GET a la API
     * @param {string} endpoint - Endpoint de la API (ej: 'company')
     * @returns {Promise<Object>} Datos de la respuesta
     */
    async get(endpoint) {
        try {
            const response = await fetch(`${this.baseURL}/${endpoint}`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (!data.success) {
                throw new Error(data.message || 'Error en la respuesta de la API');
            }
            
            return data.data;
        } catch (error) {
            console.error(`Error fetching ${endpoint}:`, error);
            throw error;
        }
    },
    
    /**
     * Obtener información de la compañía
     * @returns {Promise<Object>} Datos completos de la compañía
     */
    async getCompany() {
        return await this.get('company');
    }
};

// Exportar para uso global
window.API = API;
