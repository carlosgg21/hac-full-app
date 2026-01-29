/**
 * API Client for H.A.C. Renovation Backend
 * Handles all API requests from the frontend
 */

const API = {
    // Base URL for the API (relative; use data-api-base on <html> to override, e.g. /hac-tests/backend/api)
    get baseURL() {
        const base = document.documentElement.getAttribute('data-api-base');
        return (base ? base.replace(/\/?$/, '') : '') + '/backend/api';
    },

    /**
     * GET request to the API
     * @param {string} endpoint - API endpoint (e.g. 'company', 'services')
     * @param {string} [query] - Optional query string (e.g. 'fields=id,name&order_by=name ASC')
     * @returns {Promise<Object>} Response data
     */
    async get(endpoint, query = '') {
        const url = query ? `${this.baseURL}/${endpoint}?${query}` : `${this.baseURL}/${endpoint}`;
        try {
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            if (!data.success) {
                throw new Error(data.message || 'API response error');
            }
            return data.data;
        } catch (error) {
            console.error(`Error fetching ${endpoint}:`, error);
            throw error;
        }
    },

    /**
     * POST request to the API
     * @param {string} endpoint - API endpoint (e.g. 'quote-request')
     * @param {Object} body - JSON body
     * @returns {Promise<Object>} Response data
     */
    async post(endpoint, body) {
        try {
            const response = await fetch(`${this.baseURL}/${endpoint}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(body)
            });
            const data = await response.json();
            if (!data.success) {
                throw new Error(data.message || 'API response error');
            }
            return data.data || data;
        } catch (error) {
            console.error(`Error posting to ${endpoint}:`, error);
            throw error;
        }
    },

    /**
     * Get company information
     * @returns {Promise<Object>} Company data
     */
    async getCompany() {
        return await this.get('company');
    },

    /**
     * Get active services (id, name, description), ordered by name
     * @returns {Promise<Array>} List of services
     */
    async getServices() {
        return await this.get('services');
    }
};

// Exportar para uso global
window.API = API;
