// Main entry point - initializes all modules
// Initialize everything when DOM is ready
document.addEventListener("DOMContentLoaded", () => {
  // Initialize UI components (language, scroll effects, mobile menu)
  initUI()
  
  // Initialize wizard (must be before forms as forms depends on wizard)
  initWizard()
  
  // Initialize forms (submit handlers)
  initForms()
  
  // Load company data from API
  if (typeof API !== 'undefined') {
    loadCompanyData();
  }
})

/**
 * Cargar datos de la compañía desde la API y actualizar el DOM
 */
async function loadCompanyData() {
  const dynamicElements = document.querySelectorAll('.dynamic-content');
  
  // Los elementos ya están ocultos por CSS (clase dynamic-content)
  // No necesitamos agregar clase 'loading', solo esperar a que carguen los datos
  
  try {
    const company = await API.getCompany();
    
    if (company && company.info) {
      const info = company.info;
      
      // Actualizar valores ANTES de mostrar (mientras están ocultos)
      updateElement('yearsExperience', info.years_experience, '+');
      updateElement('totalProjects', info.total_projects, '+');
      updateElement('clientSatisfaction', info.client_satisfaction, '%', true);
    }
    
    // Revelar contenido con animación (pequeño delay para que el navegador procese los cambios)
    setTimeout(() => {
      dynamicElements.forEach((el, index) => {
        // Animación escalonada para cada elemento (efecto cascada)
        setTimeout(() => {
          el.classList.add('loaded');
        }, index * 100); // 100ms de diferencia entre cada elemento
      });
    }, 50);
    
  } catch (error) {
    console.error('Error loading company data:', error);
    
    // En caso de error, mostrar valores por defecto con animación
    setTimeout(() => {
      dynamicElements.forEach((el, index) => {
        setTimeout(() => {
          el.classList.add('loaded');
        }, index * 100);
      });
    }, 50);
  }
}

/**
 * Actualizar un elemento del DOM con un valor
 * @param {string} id - ID del elemento
 * @param {number} value - Valor a mostrar
 * @param {string} suffix - Sufijo a agregar (ej: '+', '%')
 * @param {boolean} isPercentage - Si es true, redondea el valor
 */
function updateElement(id, value, suffix = '', isPercentage = false) {
  const el = document.getElementById(id);
  if (el && value !== undefined && value !== null) {
    if (isPercentage) {
      el.textContent = `${Math.round(value)}${suffix}`;
    } else {
      el.textContent = `${value}${suffix}`;
    }
  }
}
