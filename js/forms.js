// Initialize forms
function initForms() {
  const quoteWizardForm = getWizardForm()
  if (!quoteWizardForm) {
    console.warn("Quote wizard form not found")
    return
  }

  const wizardFunctions = getWizardFunctions()
  const wizardSubmitBtn = document.getElementById("wizardSubmitBtn")

  quoteWizardForm.addEventListener("submit", async (e) => {
    e.preventDefault()

    const { isBathroomProject, isKitchenProject, validateCurrentStep, closeWizard } = wizardFunctions

    // Remove required attribute from all hidden fields before validation
    const isBathroom = isBathroomProject()
    const isKitchen = isKitchenProject()
    if (isBathroom) {
      const defaultFields = document.querySelectorAll(".wizard-step-default [required], .wizard-step-kitchen [required]")
      defaultFields.forEach(field => {
        field.removeAttribute("required")
      })
    } else if (isKitchen) {
      const defaultFields = document.querySelectorAll(".wizard-step-default [required], .wizard-step-bathroom [required]")
      defaultFields.forEach(field => {
        field.removeAttribute("required")
      })
    } else {
      const specialFields = document.querySelectorAll(".wizard-step-bathroom [required], .wizard-step-kitchen [required]")
      specialFields.forEach(field => {
        field.removeAttribute("required")
      })
    }

    if (!validateCurrentStep()) {
      return
    }

    const formData = new FormData(quoteWizardForm)

    const data = {
      name: formData.get("name"),
      email: formData.get("email"),
      phone: formData.get("phone"),
      message: formData.get("message") || "",
      is_owner: formData.get("is_owner") ? "TRUE" : "FALSE",
      address: formData.get("address"),
      portfolio_project: formData.get("portfolio_project") || "",
      privacy_policy: formData.get("privacy_policy") ? "TRUE" : "FALSE",
    }

    const { calculateBathroomCategory, calculateKitchenCategory } = wizardFunctions

    if (isBathroom) {
      data.project_type = "Bathroom Remodel"
      data.bathroom_category = calculateBathroomCategory()
      data.bathroom_goal = document.querySelector('input[name="bathroom_goal"]:checked')?.value || ""
      data.bathroom_layout = document.querySelector('input[name="bathroom_layout"]:checked')?.value || ""
      data.bathroom_shower_tub = document.querySelector('input[name="bathroom_shower_tub"]:checked')?.value || ""
      data.bathroom_finishes = document.querySelector('input[name="bathroom_finishes"]:checked')?.value || ""
      data.bathroom_vanity = document.querySelector('input[name="bathroom_vanity"]:checked')?.value || ""
      data.bathroom_durability = document.querySelector('input[name="bathroom_durability"]:checked')?.value || ""
      data.bathroom_budget = document.querySelector('input[name="bathroom_budget"]:checked')?.value || ""
      data.bathroom_upgrades = Array.from(document.querySelectorAll('input[name="bathroom_upgrades"]:checked'))
        .map(cb => cb.value)
        .join(", ")
    } else if (isKitchen) {
      data.project_type = "Kitchen Renovation"
      data.kitchen_category = calculateKitchenCategory()
      data.kitchen_goal = document.querySelector('input[name="kitchen_goal"]:checked')?.value || ""
      data.kitchen_layout = document.querySelector('input[name="kitchen_layout"]:checked')?.value || ""
      data.kitchen_cabinetry = document.querySelector('input[name="kitchen_cabinetry"]:checked')?.value || ""
      data.kitchen_finishes = document.querySelector('input[name="kitchen_finishes"]:checked')?.value || ""
      data.kitchen_countertop = document.querySelector('input[name="kitchen_countertop"]:checked')?.value || ""
      data.kitchen_appliances = document.querySelector('input[name="kitchen_appliances"]:checked')?.value || ""
      data.kitchen_durability = document.querySelector('input[name="kitchen_durability"]:checked')?.value || ""
      data.kitchen_budget = document.querySelector('input[name="kitchen_budget"]:checked')?.value || ""
      data.kitchen_upgrades = Array.from(document.querySelectorAll('input[name="kitchen_upgrades"]:checked'))
        .map(cb => cb.value)
        .join(", ")
    } else {
      data.project_type = formData.get("project_type") || ""
      data.property_type = formData.get("property_type") || ""
      data.square_feet = formData.get("square_feet") || ""
      data.budget = formData.get("budget") || ""
      data.timeline = formData.get("timeline") || ""
      data.preferred_contact = formData.get("preferred_contact") || ""
    }

    if (wizardSubmitBtn) {
      wizardSubmitBtn.disabled = true
      wizardSubmitBtn.textContent = currentLanguage === "en" ? "Submitting..." : "Envoi en cours..."
    }

    try {
      // TODO: POST to backend API (e.g. /backend/api/quote-request) when endpoint is ready
      // const apiBase = window.location.origin + (window.BASE_PATH_URL || '/backend')
      // const res = await fetch(apiBase + '/api/quote-request', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ client: {...}, answers: {...}, captcha_answer, csrf_token }) })

      const successMessage = currentLanguage === "en"
        ? "Thank you! Your quote request has been submitted. We will contact you soon."
        : "Merci! Votre demande de devis a été soumise. Nous vous contacterons bientôt."

      alert(successMessage)

      quoteWizardForm.reset()
      const contactForm = document.getElementById("contactForm")
      if (contactForm) {
        contactForm.reset()
      }
      closeWizard()
    } catch (error) {
      console.error("Error:", error)
      const errorMessage = currentLanguage === "en"
        ? "There was an error submitting your request. Please try again or contact us directly."
        : "Une erreur s'est produite lors de l'envoi de votre demande. Veuillez réessayer ou nous contacter directement."
      alert(errorMessage)
    } finally {
      if (wizardSubmitBtn) {
        wizardSubmitBtn.disabled = false
        wizardSubmitBtn.textContent = currentLanguage === "en" ? "Submit Quote" : "Soumettre le devis"
      }
    }
  })
}
