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
    
    updateFooterFromCompany(company);
    
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
    
    // Mostrar footer estático (fade-in) cuando la API falla
    const footerContent = document.querySelector('.footer-content');
    if (footerContent) footerContent.classList.add('footer-loaded');
    
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
 * Rellena el footer con datos de la API company
 */
function updateFooterFromCompany(company) {
  const footerContent = document.querySelector('.footer-content');
  if (!footerContent) return;
  if (!company) {
    footerContent.classList.add('footer-loaded');
    return;
  }

  const logoEl = document.getElementById('footerLogo');
  if (logoEl) {
    logoEl.src = company.logo || 'public/logo.png';
    if (!company.logo) logoEl.setAttribute('alt', (company.acronym || company.name || 'Logo') + ' Logo');
  }

  const nameEl = document.getElementById('footerName');
  if (nameEl) {
    const acr = (company.acronym || '').trim();
    const fullName = (company.name || '').trim();
    const line1 = escapeHtml(acr || fullName || '');
    let line2 = '';
    if (acr && fullName) {
      const rest = fullName.replace(new RegExp('^' + acr.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + '\\s*', 'i'), '').trim();
      line2 = escapeHtml(rest || fullName);
    }
    nameEl.innerHTML = line2
      ? `<span class="font-bold text-lg leading-none">${line1}</span><span class="text-xs text-gray-300 uppercase">${line2}</span>`
      : `<span class="font-bold text-lg leading-none">${line1}</span>`;
  }

  const taglineEl = document.getElementById('footerTagline');
  if (taglineEl) {
    const tagline = (company.slogan || (company.info && company.info.about_text) || taglineEl.textContent || '').trim();
    taglineEl.textContent = tagline || 'Professional construction and renovation services you can trust.';
  }

  const rbqEl = document.getElementById('footerRbq');
  if (rbqEl) {
    if (company.rbq_number) {
      rbqEl.textContent = 'RBQ: ' + escapeHtml(String(company.rbq_number));
      rbqEl.style.display = '';
    } else {
      rbqEl.style.display = 'none';
    }
  }

  const phonesEl = document.getElementById('footerPhones');
  if (phonesEl) {
    const phones = [company.phone, company.other_phone_number].filter(Boolean);
    const telLinks = phones.map(p => {
      const digits = String(p).replace(/\D/g, '');
      return `<a href="tel:${escapeHtmlAttr(digits)}" class="hover:text-accent">${escapeHtml(formatPhoneDisplay(p))}</a>`;
    });
    phonesEl.innerHTML = telLinks.join('<br>') || phonesEl.innerHTML;
  }

  const emailEl = document.getElementById('footerEmail');
  if (emailEl) {
    const email = (company.email || '').trim();
    if (email) {
      emailEl.href = 'mailto:' + escapeHtmlAttr(email);
      emailEl.textContent = email;
    }
  }

  const addressEl = document.getElementById('footerAddress');
  if (addressEl) addressEl.textContent = company.address != null ? escapeHtml(String(company.address)) : addressEl.textContent;

  const socialEl = document.getElementById('footerSocial');
  if (socialEl && company.social_media && typeof company.social_media === 'object') {
    const order = ['facebook', 'instagram', 'twitter', 'linkedin', 'youtube'];
    const icons = {
      facebook: '<path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>',
      instagram: '<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>',
      twitter: '<path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>',
      linkedin: '<path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>',
      youtube: '<path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>'
    };
    let html = '';
    order.forEach(key => {
      const url = (company.social_media[key] || '').trim();
      if (!url) return;
      const path = icons[key];
      if (!path) return;
      html += `<a href="${escapeHtmlAttr(url)}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-accent transition-colors"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">${path}</svg></a>`;
    });
    if (html) socialEl.innerHTML = html;
  }

  const copyrightEl = document.getElementById('footerCopyright');
  if (copyrightEl) {
    const year = new Date().getFullYear();
    const name = escapeHtml(company.name || 'H.A.C. Renovation Inc.');
    const privacyLink = '<a href="privacy-policy.html" target="_blank" rel="noopener noreferrer" class="hover:text-accent transition-colors" data-translate="footer_privacy">Privacy Policy</a>';
    copyrightEl.innerHTML = `&copy; ${year} ${name}. All rights reserved. | ${privacyLink}`;
  }

  footerContent.classList.add('footer-loaded');
}

function formatPhoneDisplay(phone) {
  if (!phone) return '';
  const d = String(phone).replace(/\D/g, '');
  if (d.length === 10) return `(${d.slice(0,3)}) ${d.slice(3,6)}-${d.slice(6)}`;
  if (d.length === 11 && d[0] === '1') return `(${d.slice(1,4)}) ${d.slice(4,7)}-${d.slice(7)}`;
  return phone;
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
 * Load services from API. Wizard service select is populated by wizard.js when opened.
 * Our Services section keeps the 6 static cards from index.html.
 */
async function loadServicesFromAPI() {
  try {
    await API.getServices()
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
