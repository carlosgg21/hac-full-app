/**
 * Wizard - Dynamic quote form with questions from API
 * Step 1: Basic info + Service select (from API)
 * Steps 2-N: Questions by form_position (from API) or Math+Review if no questions
 * Last step: Math verification + Review + Submit
 */

let currentStep = 1
let totalSteps = 2
let wizardState = {
  questions: [],
  serviceId: null,
  serviceLocked: false,
  mathQuestion: '',
  mathAnswer: ''
}

let quoteWizardModal, openQuoteWizardBtn, closeWizardBtn, quoteWizardForm
let wizardNextBtn, wizardPrevBtn, wizardSubmitBtn
let wizardStepsContainer

// Build HTML for a single question
function renderQuestion(q) {
  const opts = q.options ? (Array.isArray(q.options) ? q.options : JSON.parse(q.options || '[]')) : []
  const req = q.is_required ? 'required' : ''
  const name = `q_${q.id}`
  const inputFullClass = 'w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-accent focus:border-accent transition-colors'
  const inputRadioClass = 'mt-1 flex-shrink-0 w-4 h-4 text-accent border-gray-300 rounded focus:ring-accent'
  const labelClass = 'flex items-start justify-start gap-3 p-3 border border-gray-300 rounded-md hover:bg-gray-50 cursor-pointer text-left'

  let html = `<div class="mb-6 text-left" data-question-id="${q.id}"><label class="block text-sm font-medium text-gray-700 mb-3">${escapeHtml(q.question_text)}${q.is_required ? ' *' : ''}</label>`

  switch (q.question_type) {
    case 'radio':
      html += '<div class="space-y-2">'
      opts.forEach((opt) => {
        const val = typeof opt === 'object' ? opt.text : opt
        html += `<label class="${labelClass}"><input type="radio" name="${name}" value="${escapeHtml(val)}" ${req} class="${inputRadioClass}"><span class="flex-1 text-left">${escapeHtml(val)}</span></label>`
      })
      html += '</div>'
      break
    case 'checkbox':
      html += '<div class="space-y-2">'
      opts.forEach((opt) => {
        const val = typeof opt === 'object' ? opt.text : opt
        html += `<label class="${labelClass}"><input type="checkbox" name="${name}" value="${escapeHtml(val)}" class="${inputRadioClass}"><span class="flex-1 text-left">${escapeHtml(val)}</span></label>`
      })
      html += '</div>'
      break
    case 'select':
      html += `<select name="${name}" ${req} class="${inputFullClass}"><option value="">Select...</option>`
      opts.forEach((opt) => {
        const val = typeof opt === 'object' ? opt.text : opt
        html += `<option value="${escapeHtml(val)}">${escapeHtml(val)}</option>`
      })
      html += '</select>'
      break
    case 'number':
      html += `<input type="number" name="${name}" ${req} class="${inputFullClass}" min="0" placeholder="">`
      break
    case 'textarea':
      html += `<textarea name="${name}" ${req} class="${inputFullClass}" rows="4"></textarea>`
      break
    default:
      html += `<input type="text" name="${name}" ${req} class="${inputFullClass}">`
  }
  html += '</div>'
  return html
}

function escapeHtml(s) {
  const d = document.createElement('div')
  d.textContent = s
  return d.innerHTML
}

function buildMathReviewStepHtml(stepNum) {
  generateMathQuestion()
  return `
  <div class="wizard-step-content wizard-step-dynamic hidden text-left" data-step="${stepNum}">
    <h3 class="text-xl font-bold text-primary mb-6 text-left">Review & Submit</h3>
    <div class="space-y-6 text-left">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Verification: What is ${wizardState.mathQuestion}? *</label>
        <input type="text" id="wizardMathAnswer" name="math_answer" required placeholder="Enter the result"
               class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-accent focus:border-accent transition-colors"
               data-math-question="${escapeHtml(wizardState.mathQuestion)}" data-math-expected="${wizardState.mathAnswer}">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notes (Optional)</label>
        <textarea id="wizardMessage" name="message" rows="4"
                  class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-accent focus:border-accent transition-colors"
                  placeholder="Any additional information..."></textarea>
      </div>
      <div class="wizard-review-invoice text-left">
        <div class="border border-gray-200 rounded-t-lg bg-primary text-white px-4 py-3">
          <h4 class="font-semibold text-lg" data-translate="wizard_review_title">Review Your Information</h4>
        </div>
        <div id="wizardReview" class="border border-t-0 border-gray-200 rounded-b-lg overflow-hidden"></div>
      </div>
    </div>
  </div>`
}

// Generate math question (e.g. 3 + 5 = 8)
function generateMathQuestion() {
  const a = Math.floor(Math.random() * 9) + 1
  const b = Math.floor(Math.random() * 9) + 1
  wizardState.mathQuestion = `${a} + ${b}`
  wizardState.mathAnswer = String(a + b)
}

// Build dynamic steps from questions
async function loadQuestionsAndBuildSteps(serviceId) {
  const container = document.getElementById('wizardDynamicSteps')
  if (!container) return

  container.innerHTML = '<p class="text-gray-500 py-4">Loading...</p>'

  try {
    const questions = typeof API !== 'undefined' && API.getQuestions
      ? await API.getQuestions(serviceId)
      : []

    wizardState.questions = questions || []

    const byPosition = {}
    ;(questions || []).forEach(q => {
      const pos = parseInt(q.form_position) || 2
      if (!byPosition[pos]) byPosition[pos] = []
      byPosition[pos].push(q)
    })

    const positions = Object.keys(byPosition).map(Number).sort((a, b) => a - b)
    const stepsHtml = []
    let stepNum = 2

    // If no questions: only math + review step (step 2)
    if (positions.length === 0) {
      container.innerHTML = buildMathReviewStepHtml(2)
      totalSteps = 2
      rebuildStepIndicators()
      updateWizardSteps()
      return
    }

    positions.forEach(pos => {
      const qs = byPosition[pos]
      const title = positions.length > 1 ? `Step ${stepNum}` : 'Project Details'
      let stepHtml = `<div class="wizard-step-content wizard-step-dynamic hidden text-left" data-step="${stepNum}"><h3 class="text-xl font-bold text-primary mb-6 text-left">${title}</h3><div class="space-y-6 text-left">`
      qs.forEach(q => { stepHtml += renderQuestion(q) })
      stepHtml += '</div></div>'
      stepsHtml.push(stepHtml)
      stepNum++
    })

    const lastStep = stepNum
    const reviewHtml = buildMathReviewStepHtml(lastStep)
    container.innerHTML = stepsHtml.join('') + reviewHtml
    totalSteps = lastStep
    rebuildStepIndicators()
    updateWizardSteps()
  } catch (err) {
    console.error('Error loading questions:', err)
    container.innerHTML = '<p class="text-red-500 py-4">Error loading questions. Please try again.</p>'
    totalSteps = 2
    rebuildStepIndicators()
  }
}

// Rebuild step indicator dots
function rebuildStepIndicators() {
  const container = document.querySelector('.wizard-steps')
  if (!container) return
  let html = '<div class="wizard-step active" data-step="1"><div class="wizard-step-number">1</div><div class="wizard-step-label">Basic Info</div></div>'
  for (let i = 2; i <= totalSteps; i++) {
    const label = i === totalSteps ? 'Review' : `Step ${i}`
    html += `<div class="wizard-step" data-step="${i}"><div class="wizard-step-number">${i}</div><div class="wizard-step-label">${label}</div></div>`
  }
  container.innerHTML = html
}

function updateReview() {
  const div = document.getElementById('wizardReview')
  if (!div || !quoteWizardForm) return
  const fd = new FormData(quoteWizardForm)
  const serviceSelect = document.getElementById('wizardServiceId')
  const serviceName = serviceSelect?.options[serviceSelect.selectedIndex]?.textContent || '-'

  // Section 1: Client / quote info (invoice-style block)
  const clientLabels = typeof translations !== 'undefined' && translations.wizard_review_name
    ? { name: translations.wizard_review_name, email: translations.wizard_review_email, phone: translations.wizard_review_phone, address: translations.wizard_review_address, service: translations.wizard_review_service, owner: translations.wizard_review_owner }
    : { name: 'Name', email: 'Email', phone: 'Phone', address: 'Address', service: 'Service', owner: 'Property Owner' }
  let html = `
    <div class="bg-gray-50 px-4 py-4 border-b border-gray-200">
      <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Customer &amp; quote details</div>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2 text-sm">
        <div class="flex"><span class="text-gray-500 w-28 flex-shrink-0">${escapeHtml(clientLabels.name)}</span><span class="text-gray-900 font-medium">${escapeHtml(String(fd.get('name') || '-'))}</span></div>
        <div class="flex"><span class="text-gray-500 w-28 flex-shrink-0">${escapeHtml(clientLabels.email)}</span><span class="text-gray-900 font-medium">${escapeHtml(String(fd.get('email') || '-'))}</span></div>
        <div class="flex"><span class="text-gray-500 w-28 flex-shrink-0">${escapeHtml(clientLabels.phone)}</span><span class="text-gray-900">${escapeHtml(String(fd.get('phone') || '-'))}</span></div>
        <div class="flex sm:col-span-2"><span class="text-gray-500 w-28 flex-shrink-0">${escapeHtml(clientLabels.address)}</span><span class="text-gray-900">${escapeHtml(String(fd.get('address') || '-'))}</span></div>
        <div class="flex"><span class="text-gray-500 w-28 flex-shrink-0">${escapeHtml(clientLabels.service)}</span><span class="text-gray-900 font-medium">${escapeHtml(String(serviceName))}</span></div>
        <div class="flex"><span class="text-gray-500 w-28 flex-shrink-0">${escapeHtml(clientLabels.owner)}</span><span class="text-gray-900">${fd.get('is_owner') ? 'Yes' : 'No'}</span></div>
      </div>
    </div>
    <div class="px-4 py-3">
      <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Project details — questions &amp; answers</div>
      <table class="w-full text-sm border-collapse">
        <thead><tr class="border-b border-gray-200"><th class="text-left py-2 pr-4 text-gray-500 font-semibold">Question</th><th class="text-left py-2 text-gray-500 font-semibold">Answer</th></tr></thead>
        <tbody>`
  wizardState.questions.forEach(q => {
    const name = `q_${q.id}`
    const val = q.question_type === 'checkbox'
      ? Array.from(document.querySelectorAll(`input[name="${name}"]:checked`)).map(c => c.value).join(', ') || '—'
      : (document.querySelector(`[name="${name}"]`)?.value || '—')
    html += `<tr class="border-b border-gray-100"><td class="py-2 pr-4 text-gray-700 align-top">${escapeHtml(q.question_text)}</td><td class="py-2 text-gray-900 align-top">${escapeHtml(String(val))}</td></tr>`
  })
  html += `</tbody></table></div>`
  div.innerHTML = html
}

const VALIDATION_ERROR_CLASSES = ['border-red-500', 'ring-2', 'ring-red-500']

function clearValidationErrors(container) {
  if (!container) return
  container.querySelectorAll('input, select, textarea').forEach(el => el.classList.remove(...VALIDATION_ERROR_CLASSES))
  container.querySelectorAll('.validation-error-group').forEach(el => el.classList.remove('validation-error-group'))
}

function markValidationError(el) {
  if (!el) return
  el.classList.add(...VALIDATION_ERROR_CLASSES)
  setTimeout(() => el.classList.remove(...VALIDATION_ERROR_CLASSES), 4000)
}

function markQuestionGroupError(container) {
  if (!container) return
  container.classList.add('validation-error-group')
  setTimeout(() => container.classList.remove('validation-error-group'), 4000)
}

function scrollToFirstInvalid(content) {
  const firstError = content.querySelector('.border-red-500, .validation-error-group')
  if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' })
}

function validateCurrentStep() {
  const content = document.querySelector(`.wizard-step-content[data-step="${currentStep}"].active`)
  if (!content) return false

  clearValidationErrors(content)

  if (currentStep === 1) {
    let step1Ok = true
    const fields = [
      { id: 'wizardName', required: true },
      { id: 'wizardEmail', required: true },
      { id: 'wizardPhone', required: true },
      { id: 'wizardAddress', required: true },
      { id: 'wizardServiceId', required: true, isSelect: true },
      { id: 'wizardPrivacyPolicy', required: true, isCheckbox: true }
    ]
    fields.forEach(({ id, required, isSelect, isCheckbox }) => {
      if (!required) return
      const el = document.getElementById(id)
      if (!el) return
      let valid = false
      if (isCheckbox) valid = el.checked
      else if (isSelect) valid = !!el.value
      else valid = !!String(el.value || '').trim()
      if (!valid) {
        markValidationError(el)
        step1Ok = false
      }
    })
    if (!step1Ok) {
      scrollToFirstInvalid(content)
      return false
    }
  }

  const required = content.querySelectorAll('input[required], select[required]')
  let questionsOk = true
  const radioGroupsChecked = new Set()
  required.forEach(f => {
    if (f.type === 'radio') {
      if (radioGroupsChecked.has(f.name)) return
      radioGroupsChecked.add(f.name)
      const group = content.querySelectorAll(`input[name="${f.name}"][required]`)
      if (!Array.from(group).some(r => r.checked)) {
        const qContainer = f.closest('[data-question-id]')
        if (qContainer) markQuestionGroupError(qContainer)
        questionsOk = false
      }
    } else if (f.type === 'checkbox') {
      if (f.required && !f.checked) {
        markValidationError(f)
        questionsOk = false
      }
    } else if (!String(f.value || '').trim()) {
      markValidationError(f)
      questionsOk = false
    }
  })
  if (!questionsOk) {
    scrollToFirstInvalid(content)
    return false
  }

  if (currentStep === totalSteps) {
    const mathInput = document.getElementById('wizardMathAnswer')
    if (mathInput) {
      const expected = mathInput.getAttribute('data-math-expected')
      if (mathInput.value.trim() !== expected) {
        markValidationError(mathInput)
        scrollToFirstInvalid(content)
        return false
      }
    }
  }
  return true
}

function updateWizardSteps() {
  document.querySelectorAll('.wizard-step').forEach((el, i) => {
    const n = i + 1
    el.classList.remove('active', 'completed')
    if (n < currentStep) el.classList.add('completed')
    else if (n === currentStep) el.classList.add('active')
  })

  document.querySelectorAll('.wizard-step-content').forEach(el => {
    const n = parseInt(el.getAttribute('data-step'))
    el.classList.remove('active')
    el.classList.add('hidden')
    if (n === currentStep) {
      el.classList.add('active')
      el.classList.remove('hidden')
    }
  })

  if (wizardPrevBtn) wizardPrevBtn.classList.toggle('hidden', currentStep === 1)
  if (wizardNextBtn) wizardNextBtn.classList.toggle('hidden', currentStep === totalSteps)
  if (wizardSubmitBtn) wizardSubmitBtn.classList.toggle('hidden', currentStep !== totalSteps)

  if (currentStep === totalSteps) updateReview()
}

async function loadServicesIntoSelect(lockServiceId = null) {
  const sel = document.getElementById('wizardServiceId')
  const lockedInput = document.getElementById('wizardServiceLocked')
  if (!sel) return
  sel.innerHTML = '<option value="">Loading...</option>'
  try {
    const services = typeof API !== 'undefined' && API.getServices ? await API.getServices() : []
    sel.innerHTML = '<option value="">Select a service...</option>'
    services.forEach(s => {
      const opt = document.createElement('option')
      opt.value = s.id
      opt.textContent = s.name
      if (lockServiceId != null) {
        opt.disabled = s.id != lockServiceId
      }
      sel.appendChild(opt)
    })
    if (lockServiceId != null) {
      sel.value = String(lockServiceId)
      sel.classList.add('service-locked')
      if (lockedInput) lockedInput.value = '1'
    } else {
      sel.classList.remove('service-locked')
      if (lockedInput) lockedInput.value = '0'
    }
  } catch (e) {
    sel.innerHTML = '<option value="">Error loading services</option>'
  }
}

async function openWizard(serviceId = null, lockService = false) {
  currentStep = 1
  quoteWizardModal?.classList.remove('hidden')
  document.body.style.overflow = 'hidden'
  wizardState.serviceLocked = lockService
  wizardState.serviceId = serviceId

  await loadServicesIntoSelect(lockService ? serviceId : null)

  const sid = serviceId || parseInt(document.getElementById('wizardServiceId')?.value || '0')
  if (sid) await loadQuestionsAndBuildSteps(sid)
  else {
    totalSteps = 2
    rebuildStepIndicators()
    document.getElementById('wizardDynamicSteps').innerHTML = '<p class="text-gray-500 py-4">Select a service to continue.</p>'
    updateWizardSteps()
  }
}

function closeWizard() {
  quoteWizardModal?.classList.add('hidden')
  document.body.style.overflow = ''
  currentStep = 1
  const sel = document.getElementById('wizardServiceId')
  if (sel) sel.classList.remove('service-locked')
  const locked = document.getElementById('wizardServiceLocked')
  if (locked) locked.value = '0'
  updateWizardSteps()
}

function initWizard() {
  quoteWizardModal = document.getElementById('quoteWizardModal')
  openQuoteWizardBtn = document.getElementById('openQuoteWizardBtn')
  closeWizardBtn = document.getElementById('closeWizardBtn')
  quoteWizardForm = document.getElementById('quoteWizardForm')
  wizardNextBtn = document.getElementById('wizardNextBtn')
  wizardPrevBtn = document.getElementById('wizardPrevBtn')
  wizardSubmitBtn = document.getElementById('wizardSubmitBtn')

  if (!quoteWizardModal || !quoteWizardForm) return

  if (openQuoteWizardBtn) {
    openQuoteWizardBtn.addEventListener('click', () => openWizard(null, false))
  }

  document.querySelectorAll('.portfolio-quote-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const sid = btn.getAttribute('data-service-id')
      const serviceId = sid ? parseInt(sid) : null
      openWizard(serviceId, !!serviceId)
    })
  })

  const serviceSelect = document.getElementById('wizardServiceId')
  if (serviceSelect) {
    serviceSelect.addEventListener('change', async () => {
      if (wizardState.serviceLocked) return
      const sid = parseInt(serviceSelect.value || '0')
      if (sid) await loadQuestionsAndBuildSteps(sid)
    })
  }

  if (closeWizardBtn) closeWizardBtn.addEventListener('click', closeWizard)
  if (quoteWizardModal) {
    quoteWizardModal.addEventListener('click', (e) => {
      if (e.target === quoteWizardModal || e.target.classList.contains('wizard-overlay')) closeWizard()
    })
  }

  if (wizardNextBtn) {
    wizardNextBtn.addEventListener('click', () => {
      if (validateCurrentStep()) {
        currentStep++
        updateWizardSteps()
      }
    })
  }
  if (wizardPrevBtn) {
    wizardPrevBtn.addEventListener('click', () => {
      currentStep--
      updateWizardSteps()
    })
  }

  rebuildStepIndicators()
  updateWizardSteps()
}

function getWizardFunctions() {
  return {
    validateCurrentStep,
    closeWizard,
    getCurrentStep: () => currentStep,
    getTotalSteps: () => totalSteps,
    getWizardState: () => ({ ...wizardState })
  }
}

function getWizardForm() {
  return quoteWizardForm
}
