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

    const { validateCurrentStep, closeWizard, getWizardState } = wizardFunctions

    if (!validateCurrentStep()) {
      return
    }

    const formData = new FormData(quoteWizardForm)
    const state = getWizardState ? getWizardState() : {}

    const answers = []
    state.questions.forEach((q) => {
      const name = `q_${q.id}`
      if (q.question_type === "checkbox") {
        const checked = document.querySelectorAll(`input[name="${name}"]:checked`)
        const vals = Array.from(checked).map((c) => c.value)
        const ans = { question_id: q.id, answer_text: vals.join(", "), answer_value: null }
        if (vals.length) ans.answer_json = vals
        answers.push(ans)
      } else {
        const el = document.querySelector(`[name="${name}"]:checked`) || document.querySelector(`[name="${name}"]`)
        if (!el) return
        const val = el.value || ""
        if (q.question_type === "number" && val) {
          answers.push({ question_id: q.id, answer_text: val, answer_value: parseFloat(val) || null })
        } else {
          answers.push({ question_id: q.id, answer_text: val, answer_value: null })
        }
      }
    })

    const rawServiceId = formData.get("service_id")
    const stateServiceId = state.serviceId
    const serviceId = parseInt(rawServiceId || "0") || (stateServiceId ? parseInt(stateServiceId) : 0)

    const data = {
      name: formData.get("name"),
      email: formData.get("email"),
      phone: formData.get("phone"),
      address: formData.get("address"),
      message: formData.get("message") || "",
      is_owner: formData.get("is_owner") ? "TRUE" : "FALSE",
      privacy_policy: formData.get("privacy_policy") ? "TRUE" : "FALSE",
      service_id: serviceId,
      math_question: state.mathQuestion || "",
      math_answer: formData.get("math_answer") || "",
      answers
    }

    if (wizardSubmitBtn) {
      wizardSubmitBtn.disabled = true
      wizardSubmitBtn.textContent = typeof currentLanguage !== "undefined" && currentLanguage === "fr" ? "Envoi en cours..." : "Submitting..."
    }

    try {
      if (typeof API !== "undefined" && API.post) {
        await API.post("quote-request", data)
      }
      const successMessage = typeof currentLanguage !== "undefined" && currentLanguage === "fr"
        ? "Merci! Votre demande de devis a été soumise. Nous vous contacterons bientôt."
        : "Thank you! Your quote request has been submitted. We will contact you soon."
      if (typeof Notify !== "undefined") Notify.success(successMessage)
      else alert(successMessage)
      quoteWizardForm.reset()
      const contactForm = document.getElementById("contactForm")
      if (contactForm) contactForm.reset()
      closeWizard()
    } catch (error) {
      console.error("Error:", error)
      const errorMessage = typeof currentLanguage !== "undefined" && currentLanguage === "fr"
        ? (error.message || "Une erreur s'est produite lors de l'envoi de votre demande. Veuillez réessayer ou nous contacter directement.")
        : (error.message || "There was an error submitting your request. Please try again or contact us directly.")
      if (typeof Notify !== "undefined") Notify.error(errorMessage)
      else alert(errorMessage)
    } finally {
      if (wizardSubmitBtn) {
        wizardSubmitBtn.disabled = false
        wizardSubmitBtn.textContent = typeof currentLanguage !== "undefined" && currentLanguage === "fr" ? "Soumettre le devis" : "Submit Quote"
      }
    }
  })
}
