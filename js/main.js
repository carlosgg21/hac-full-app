// Main entry point - initializes all modules
document.addEventListener("DOMContentLoaded", () => {
  initUI()
  initWizard()
  initForms()

  if (typeof API !== 'undefined') {
    loadCompanyData()
    loadServicesFromAPI()
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
 * Update a DOM element's text content
 */
function updateElement(id, value, suffix = '', isPercentage = false) {
  const el = document.getElementById(id)
  if (el && value !== undefined && value !== null) {
    if (isPercentage) {
      el.textContent = `${Math.round(value)}${suffix}`
    } else {
      el.textContent = `${value}${suffix}`
    }
  }
}

/**
 * Load services from API and render services section + wizard "Project from Portfolio" select
 */
async function loadServicesFromAPI() {
  const servicesGrid = document.getElementById('servicesGrid')
  const wizardPortfolioSelect = document.getElementById('wizardPortfolioProject')
  const wizardProjectTypeSelect = document.getElementById('wizardProjectType')

  try {
    const services = await API.getServices()
    if (!services || !Array.isArray(services)) return

    // Populate "Project from Portfolio" select in wizard with API services
    if (wizardPortfolioSelect) {
      const firstOpt = wizardPortfolioSelect.querySelector('option[value=""]')
      wizardPortfolioSelect.innerHTML = ''
      const placeholder = document.createElement('option')
      placeholder.value = ''
      placeholder.textContent = firstOpt ? firstOpt.textContent : 'Select a project...'
      wizardPortfolioSelect.appendChild(placeholder)
      services.forEach(s => {
        const opt = document.createElement('option')
        opt.value = s.name
        opt.textContent = s.name
        wizardPortfolioSelect.appendChild(opt)
      })
    }

    // Optionally add API services to "Project Type" select (keep existing options, add missing from API)
    if (wizardProjectTypeSelect) {
      const existingValues = new Set(Array.from(wizardProjectTypeSelect.querySelectorAll('option')).map(o => o.value))
      services.forEach(s => {
        if (existingValues.has(s.name)) return
        existingValues.add(s.name)
        const opt = document.createElement('option')
        opt.value = s.name
        opt.textContent = s.name
        wizardProjectTypeSelect.insertBefore(opt, wizardProjectTypeSelect.options[1] || null)
      })
    }

    // Render services section from API
    if (servicesGrid) {
      servicesGrid.innerHTML = services.map(s => `
        <div class="bg-white rounded-lg shadow-md p-5 hover:shadow-xl transition-shadow">
          <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 bg-accent/10 rounded-lg flex items-center justify-center flex-shrink-0">
              <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
              </svg>
            </div>
            <h3 class="text-lg font-bold text-primary">${escapeHtml(s.name)}</h3>
          </div>
          <p class="text-sm text-gray-600 leading-relaxed">${escapeHtml(s.description || '')}</p>
        </div>
      `).join('')
    }
  } catch (err) {
    console.error('Error loading services from API:', err)
  }
}

function escapeHtml(str) {
  if (str == null) return ''
  const div = document.createElement('div')
  div.textContent = str
  return div.innerHTML
}

function escapeHtmlAttr(str) {
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
}
