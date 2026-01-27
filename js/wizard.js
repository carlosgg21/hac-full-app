let currentStep = 1
const totalSteps = 4

// Wizard DOM elements
let quoteWizardModal, openQuoteWizardBtn, closeWizardBtn, quoteWizardForm
let wizardNextBtn, wizardPrevBtn, wizardSubmitBtn

// Check if current project is Bathroom Remodel
function isBathroomProject() {
  const portfolioProject = document.getElementById("wizardPortfolioProject")?.value
  const projectType = document.getElementById("wizardProjectType")?.value
  return portfolioProject === "Bathroom Remodel" || projectType === "Bathroom"
}

// Check if current project is Kitchen Renovation
function isKitchenProject() {
  const portfolioProject = document.getElementById("wizardPortfolioProject")?.value
  const projectType = document.getElementById("wizardProjectType")?.value
  return portfolioProject === "Kitchen Renovation" || projectType === "Kitchen"
}

// Calculate bathroom category based on answers
function calculateBathroomCategory() {
  const answers = {
    goal: document.querySelector('input[name="bathroom_goal"]:checked')?.getAttribute("data-category"),
    layout: document.querySelector('input[name="bathroom_layout"]:checked')?.getAttribute("data-category"),
    shower_tub: document.querySelector('input[name="bathroom_shower_tub"]:checked')?.getAttribute("data-category"),
    finishes: document.querySelector('input[name="bathroom_finishes"]:checked')?.getAttribute("data-category"),
    vanity: document.querySelector('input[name="bathroom_vanity"]:checked')?.getAttribute("data-category"),
    durability: document.querySelector('input[name="bathroom_durability"]:checked')?.getAttribute("data-category"),
    budget: document.querySelector('input[name="bathroom_budget"]:checked')?.getAttribute("data-category")
  }

  // Count upgrades
  const upgrades = document.querySelectorAll('input[name="bathroom_upgrades"]:checked')
  let upgradesCategory = "basic"
  if (upgrades.length === 0) {
    upgradesCategory = "basic"
  } else if (upgrades.length <= 2) {
    upgradesCategory = "mid-range"
  } else {
    upgradesCategory = "high-end"
  }

  // Map categories to points
  const categoryPoints = { "basic": 1, "mid-range": 2, "high-end": 3 }
  let totalPoints = 0
  let count = 0

  Object.values(answers).forEach(category => {
    if (category) {
      totalPoints += categoryPoints[category] || 0
      count++
    }
  })

  // Add upgrades points
  totalPoints += categoryPoints[upgradesCategory] || 0
  count++

  if (count === 0) return "Basic"

  const average = totalPoints / count

  if (average < 1.5) return "Basic"
  if (average <= 2.5) return "Mid-Range"
  return "High-End"
}

// Calculate kitchen category based on answers
function calculateKitchenCategory() {
  const answers = {
    goal: document.querySelector('input[name="kitchen_goal"]:checked')?.getAttribute("data-category"),
    layout: document.querySelector('input[name="kitchen_layout"]:checked')?.getAttribute("data-category"),
    cabinetry: document.querySelector('input[name="kitchen_cabinetry"]:checked')?.getAttribute("data-category"),
    finishes: document.querySelector('input[name="kitchen_finishes"]:checked')?.getAttribute("data-category"),
    countertop: document.querySelector('input[name="kitchen_countertop"]:checked')?.getAttribute("data-category"),
    appliances: document.querySelector('input[name="kitchen_appliances"]:checked')?.getAttribute("data-category"),
    durability: document.querySelector('input[name="kitchen_durability"]:checked')?.getAttribute("data-category"),
    budget: document.querySelector('input[name="kitchen_budget"]:checked')?.getAttribute("data-category")
  }

  // Count upgrades
  const upgrades = document.querySelectorAll('input[name="kitchen_upgrades"]:checked')
  let upgradesCategory = "basic"
  if (upgrades.length === 0) {
    upgradesCategory = "basic"
  } else if (upgrades.length <= 2) {
    upgradesCategory = "mid-range"
  } else {
    upgradesCategory = "high-end"
  }

  // Map categories to points
  const categoryPoints = { "basic": 1, "mid-range": 2, "high-end": 3 }
  let totalPoints = 0
  let count = 0

  Object.values(answers).forEach(category => {
    if (category) {
      totalPoints += categoryPoints[category] || 0
      count++
    }
  })

  // Add upgrades points
  totalPoints += categoryPoints[upgradesCategory] || 0
  count++

  if (count === 0) return "Basic"

  const average = totalPoints / count

  if (average < 1.5) return "Basic"
  if (average <= 2.5) return "Mid-Range"
  return "High-End"
}

// Update review section
function updateReview() {
  const isBathroom = isBathroomProject()
  const isKitchen = isKitchenProject()
  const formData = new FormData(quoteWizardForm)
  
  if (isBathroom) {
    const reviewDiv = document.getElementById("wizardReviewBathroom")
    const categoryDiv = document.getElementById("bathroomCategoryValue")
    const category = calculateBathroomCategory()
    
    const bathroomAnswers = {
      goal: document.querySelector('input[name="bathroom_goal"]:checked')?.closest("label")?.textContent.trim() || "Not answered",
      layout: document.querySelector('input[name="bathroom_layout"]:checked')?.closest("label")?.textContent.trim() || "Not answered",
      shower_tub: document.querySelector('input[name="bathroom_shower_tub"]:checked')?.closest("label")?.textContent.trim() || "Not answered",
      finishes: document.querySelector('input[name="bathroom_finishes"]:checked')?.closest("label")?.textContent.trim() || "Not answered",
      vanity: document.querySelector('input[name="bathroom_vanity"]:checked')?.closest("label")?.textContent.trim() || "Not answered",
      durability: document.querySelector('input[name="bathroom_durability"]:checked')?.closest("label")?.textContent.trim() || "Not answered",
      budget: document.querySelector('input[name="bathroom_budget"]:checked')?.closest("label")?.textContent.trim() || "Not answered"
    }

    const upgrades = Array.from(document.querySelectorAll('input[name="bathroom_upgrades"]:checked'))
      .map(checkbox => checkbox.closest("label")?.textContent.trim())
      .filter(Boolean)

    if (reviewDiv) {
      reviewDiv.innerHTML = `
        <p><strong>Name:</strong> ${formData.get("name") || "Not provided"}</p>
        <p><strong>Email:</strong> ${formData.get("email") || "Not provided"}</p>
        <p><strong>Phone:</strong> ${formData.get("phone") || "Not provided"}</p>
        <p><strong>Address:</strong> ${formData.get("address") || "Not provided"}</p>
        <p><strong>Property Owner:</strong> ${formData.get("is_owner") ? "Yes" : "No"}</p>
        <hr class="my-2">
        <p><strong>1. Main Goal:</strong> ${bathroomAnswers.goal}</p>
        <p><strong>2. Layout:</strong> ${bathroomAnswers.layout}</p>
        <p><strong>3. Shower/Tub:</strong> ${bathroomAnswers.shower_tub}</p>
        <p><strong>4. Finishes:</strong> ${bathroomAnswers.finishes}</p>
        <p><strong>5. Vanity:</strong> ${bathroomAnswers.vanity}</p>
        <p><strong>6. Upgrades:</strong> ${upgrades.length > 0 ? upgrades.join(", ") : "None"}</p>
        <p><strong>7. Durability:</strong> ${bathroomAnswers.durability}</p>
        <p><strong>8. Budget:</strong> ${bathroomAnswers.budget}</p>
        ${formData.get("message") ? `<p><strong>Notes:</strong> ${formData.get("message")}</p>` : ""}
      `
    }
    
    if (categoryDiv) {
      categoryDiv.textContent = category
    }
  } else if (isKitchen) {
    const reviewDiv = document.getElementById("wizardReviewKitchen")
    const categoryDiv = document.getElementById("kitchenCategoryValue")
    const category = calculateKitchenCategory()
    
    const kitchenAnswers = {
      goal: document.querySelector('input[name="kitchen_goal"]:checked')?.closest("label")?.textContent.trim() || "Not answered",
      layout: document.querySelector('input[name="kitchen_layout"]:checked')?.closest("label")?.textContent.trim() || "Not answered",
      cabinetry: document.querySelector('input[name="kitchen_cabinetry"]:checked')?.closest("label")?.textContent.trim() || "Not answered",
      finishes: document.querySelector('input[name="kitchen_finishes"]:checked')?.closest("label")?.textContent.trim() || "Not answered",
      countertop: document.querySelector('input[name="kitchen_countertop"]:checked')?.closest("label")?.textContent.trim() || "Not answered",
      appliances: document.querySelector('input[name="kitchen_appliances"]:checked')?.closest("label")?.textContent.trim() || "Not answered",
      durability: document.querySelector('input[name="kitchen_durability"]:checked')?.closest("label")?.textContent.trim() || "Not answered",
      budget: document.querySelector('input[name="kitchen_budget"]:checked')?.closest("label")?.textContent.trim() || "Not answered"
    }

    const upgrades = Array.from(document.querySelectorAll('input[name="kitchen_upgrades"]:checked'))
      .map(checkbox => checkbox.closest("label")?.textContent.trim())
      .filter(Boolean)

    if (reviewDiv) {
      reviewDiv.innerHTML = `
        <p><strong>Name:</strong> ${formData.get("name") || "Not provided"}</p>
        <p><strong>Email:</strong> ${formData.get("email") || "Not provided"}</p>
        <p><strong>Phone:</strong> ${formData.get("phone") || "Not provided"}</p>
        <p><strong>Address:</strong> ${formData.get("address") || "Not provided"}</p>
        <p><strong>Property Owner:</strong> ${formData.get("is_owner") ? "Yes" : "No"}</p>
        <hr class="my-2">
        <p><strong>1. Main Goal:</strong> ${kitchenAnswers.goal}</p>
        <p><strong>2. Layout:</strong> ${kitchenAnswers.layout}</p>
        <p><strong>3. Cabinetry:</strong> ${kitchenAnswers.cabinetry}</p>
        <p><strong>4. Finishes:</strong> ${kitchenAnswers.finishes}</p>
        <p><strong>5. Countertop:</strong> ${kitchenAnswers.countertop}</p>
        <p><strong>6. Upgrades:</strong> ${upgrades.length > 0 ? upgrades.join(", ") : "None"}</p>
        <p><strong>7. Appliances:</strong> ${kitchenAnswers.appliances}</p>
        <p><strong>8. Durability:</strong> ${kitchenAnswers.durability}</p>
        <p><strong>9. Budget:</strong> ${kitchenAnswers.budget}</p>
        ${formData.get("message") ? `<p><strong>Notes:</strong> ${formData.get("message")}</p>` : ""}
      `
    }
    
    if (categoryDiv) {
      categoryDiv.textContent = category
    }
  } else {
    const reviewDiv = document.getElementById("wizardReview")
    
    if (reviewDiv) {
      const reviewData = {
        name: formData.get("name") || "Not provided",
        email: formData.get("email") || "Not provided",
        phone: formData.get("phone") || "Not provided",
        address: formData.get("address") || "Not provided",
        is_owner: formData.get("is_owner") ? "Yes" : "No",
        portfolio_project: formData.get("portfolio_project") || "Not provided",
        project_type: formData.get("project_type") || "Not provided",
        property_type: formData.get("property_type") || "Not provided",
        square_feet: formData.get("square_feet") || "Not provided",
        budget: formData.get("budget") || "Not provided",
        timeline: formData.get("timeline") || "Not provided",
        preferred_contact: formData.get("preferred_contact") || "Not provided",
        message: formData.get("message") || "None"
      }

      reviewDiv.innerHTML = `
        <p><strong>Name:</strong> ${reviewData.name}</p>
        <p><strong>Email:</strong> ${reviewData.email}</p>
        <p><strong>Phone:</strong> ${reviewData.phone}</p>
        <p><strong>Address:</strong> ${reviewData.address}</p>
        <p><strong>Property Owner:</strong> ${reviewData.is_owner}</p>
        <p><strong>Project from Portfolio:</strong> ${reviewData.portfolio_project}</p>
        <p><strong>Project Type:</strong> ${reviewData.project_type}</p>
        <p><strong>Property Type:</strong> ${reviewData.property_type}</p>
        <p><strong>Square Feet:</strong> ${reviewData.square_feet}</p>
        <p><strong>Budget:</strong> ${reviewData.budget}</p>
        <p><strong>Timeline:</strong> ${reviewData.timeline}</p>
        <p><strong>Preferred Contact:</strong> ${reviewData.preferred_contact}</p>
        ${reviewData.message !== "None" ? `<p><strong>Notes:</strong> ${reviewData.message}</p>` : ""}
      `
    }
  }
}

// Validate current step
function validateCurrentStep() {
  const currentStepContent = document.querySelector(`.wizard-step-content[data-step="${currentStep}"].active`)
  if (!currentStepContent) return false
  
  const requiredFields = currentStepContent.querySelectorAll("input[required], select[required]")
  let isValid = true

  // Special validation for Step 1 - privacy policy checkbox
  if (currentStep === 1) {
    const privacyCheckbox = document.getElementById("wizardPrivacyPolicy")
    if (privacyCheckbox && !privacyCheckbox.checked) {
      isValid = false
      privacyCheckbox.classList.add("border-red-500", "ring-2", "ring-red-500")
      setTimeout(() => {
        privacyCheckbox.classList.remove("border-red-500", "ring-2", "ring-red-500")
      }, 3000)
    }
  }

  requiredFields.forEach((field) => {
    if (field.type === "checkbox") {
      if (!field.checked && field.required) {
        isValid = false
        field.classList.add("border-red-500", "ring-2", "ring-red-500")
        setTimeout(() => {
          field.classList.remove("border-red-500", "ring-2", "ring-red-500")
        }, 3000)
      }
    } else if (field.type === "radio") {
      const radioGroup = currentStepContent.querySelectorAll(`input[name="${field.name}"][required]`)
      const isRadioChecked = Array.from(radioGroup).some(radio => radio.checked)
      if (!isRadioChecked) {
        isValid = false
        radioGroup.forEach(radio => {
          radio.closest("label")?.classList.add("border-red-500")
          setTimeout(() => {
            radio.closest("label")?.classList.remove("border-red-500")
          }, 3000)
        })
      }
    } else if (!field.value.trim()) {
      isValid = false
      field.classList.add("border-red-500")
      setTimeout(() => {
        field.classList.remove("border-red-500")
      }, 3000)
    } else {
      field.classList.remove("border-red-500")
    }
  })

  return isValid
}

// Update wizard step indicators
function updateWizardSteps() {
  const isBathroom = isBathroomProject()
  const isKitchen = isKitchenProject()
  
  // Show/hide step groups based on project type
  const defaultSteps = document.querySelectorAll(".wizard-step-default")
  const bathroomSteps = document.querySelectorAll(".wizard-step-bathroom")
  const kitchenSteps = document.querySelectorAll(".wizard-step-kitchen")

  if (isBathroom) {
    defaultSteps.forEach(step => step.classList.add("hidden"))
    bathroomSteps.forEach(step => step.classList.remove("hidden"))
    kitchenSteps.forEach(step => step.classList.add("hidden"))
    
    // Remove required attribute from default and kitchen form fields when hidden
    const defaultRequiredFields = document.querySelectorAll(".wizard-step-default [required], .wizard-step-kitchen [required]")
    defaultRequiredFields.forEach(field => {
      field.removeAttribute("required")
      field.setAttribute("data-was-required", "true")
    })
  } else if (isKitchen) {
    defaultSteps.forEach(step => step.classList.add("hidden"))
    bathroomSteps.forEach(step => step.classList.add("hidden"))
    kitchenSteps.forEach(step => step.classList.remove("hidden"))
    
    // Remove required attribute from default and bathroom form fields when hidden
    const defaultRequiredFields = document.querySelectorAll(".wizard-step-default [required], .wizard-step-bathroom [required]")
    defaultRequiredFields.forEach(field => {
      field.removeAttribute("required")
      field.setAttribute("data-was-required", "true")
    })
  } else {
    defaultSteps.forEach(step => step.classList.remove("hidden"))
    bathroomSteps.forEach(step => step.classList.add("hidden"))
    kitchenSteps.forEach(step => step.classList.add("hidden"))
    
    // Restore required attribute for default form fields when visible
    const defaultFields = document.querySelectorAll(".wizard-step-default [data-was-required]")
    defaultFields.forEach(field => {
      field.setAttribute("required", "required")
    })
    
    // Remove required attribute from bathroom and kitchen form fields when hidden
    const specialRequiredFields = document.querySelectorAll(".wizard-step-bathroom [required], .wizard-step-kitchen [required]")
    specialRequiredFields.forEach(field => {
      field.removeAttribute("required")
      field.setAttribute("data-was-required", "true")
    })
  }

  // Update step indicators
  document.querySelectorAll(".wizard-step").forEach((step, index) => {
    const stepNum = index + 1
    step.classList.remove("active", "completed")
    if (stepNum < currentStep) {
      step.classList.add("completed")
    } else if (stepNum === currentStep) {
      step.classList.add("active")
    }
  })

  // Update step content visibility - only show active step for current project type
  document.querySelectorAll(".wizard-step-content").forEach((content) => {
    const stepNum = parseInt(content.getAttribute("data-step"))
    const isDefault = content.classList.contains("wizard-step-default")
    const isBathroomStep = content.classList.contains("wizard-step-bathroom")
    const isKitchenStep = content.classList.contains("wizard-step-kitchen")
    const isStep1 = stepNum === 1
    
    // Remove active from all
    content.classList.remove("active")
    
    // Step 1 is always visible (common for all project types)
    if (isStep1) {
      if (stepNum === currentStep) {
        content.classList.add("active")
        content.classList.remove("hidden")
      } else {
        content.classList.add("hidden")
      }
    } else {
      // Show only the active step that matches the project type
      if (stepNum === currentStep) {
        if ((isBathroom && isBathroomStep) || (isKitchen && isKitchenStep) || (!isBathroom && !isKitchen && isDefault)) {
          content.classList.add("active")
          content.classList.remove("hidden")
        } else {
          content.classList.add("hidden")
        }
      } else {
        // Hide all other steps
        content.classList.add("hidden")
      }
    }
  })

  // Update navigation buttons
  if (wizardPrevBtn) {
    wizardPrevBtn.classList.toggle("hidden", currentStep === 1)
  }
  if (wizardNextBtn) {
    wizardNextBtn.classList.toggle("hidden", currentStep === totalSteps)
  }
  if (wizardSubmitBtn) {
    wizardSubmitBtn.classList.toggle("hidden", currentStep !== totalSteps)
  }

  // Update review on step 4
  if (currentStep === 4) {
    updateReview()
  }
}

function closeWizard() {
  if (quoteWizardModal) {
    quoteWizardModal.classList.add("hidden")
  }
  document.body.style.overflow = ""
  currentStep = 1
  
  // Restore all required attributes to their original state
  const allFields = document.querySelectorAll("[data-was-required]")
  allFields.forEach(field => {
    field.setAttribute("required", "required")
    field.removeAttribute("data-was-required")
  })
  
  updateWizardSteps()
}

// Initialize wizard
function initWizard() {
  // Get wizard DOM elements
  quoteWizardModal = document.getElementById("quoteWizardModal")
  openQuoteWizardBtn = document.getElementById("openQuoteWizardBtn")
  closeWizardBtn = document.getElementById("closeWizardBtn")
  quoteWizardForm = document.getElementById("quoteWizardForm")
  wizardNextBtn = document.getElementById("wizardNextBtn")
  wizardPrevBtn = document.getElementById("wizardPrevBtn")
  wizardSubmitBtn = document.getElementById("wizardSubmitBtn")

  if (!quoteWizardModal || !quoteWizardForm) {
    console.warn("Wizard elements not found")
    return
  }

  // Open wizard and pre-fill data from contact form
  if (openQuoteWizardBtn) {
    openQuoteWizardBtn.addEventListener("click", () => {
      const contactName = document.getElementById("contactName")?.value || ""
      const contactEmail = document.getElementById("contactEmail")?.value || ""
      const contactPhone = document.getElementById("contactPhone")?.value || ""
      const contactIsOwner = document.getElementById("contactIsOwner")?.checked || false

      // Pre-fill wizard form
      const wizardName = document.getElementById("wizardName")
      const wizardEmail = document.getElementById("wizardEmail")
      const wizardPhone = document.getElementById("wizardPhone")
      const wizardIsOwner = document.getElementById("wizardIsOwner")
      
      if (wizardName) wizardName.value = contactName
      if (wizardEmail) wizardEmail.value = contactEmail
      if (wizardPhone) wizardPhone.value = contactPhone
      if (wizardIsOwner) wizardIsOwner.checked = contactIsOwner

      // Reset wizard to step 1
      currentStep = 1
      quoteWizardModal.classList.remove("hidden")
      document.body.style.overflow = "hidden"
      // Update steps after modal is visible
      setTimeout(() => {
        updateWizardSteps()
      }, 100)
    })
  }

  // Portfolio card buttons - open wizard with pre-selected project
  document.querySelectorAll(".portfolio-quote-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
      const projectName = btn.getAttribute("data-project")
      const wizardPortfolioProject = document.getElementById("wizardPortfolioProject")
      
      // Pre-select the project in the select
      if (wizardPortfolioProject && projectName) {
        wizardPortfolioProject.value = projectName
      }

      // Reset wizard to step 1
      currentStep = 1
      quoteWizardModal.classList.remove("hidden")
      document.body.style.overflow = "hidden"
      // Update steps after modal is visible
      setTimeout(() => {
        updateWizardSteps()
      }, 100)
    })
  })

  // Listen for changes in project type selects to toggle steps
  const wizardPortfolioProject = document.getElementById("wizardPortfolioProject")
  const wizardProjectType = document.getElementById("wizardProjectType")
  
  if (wizardPortfolioProject) {
    wizardPortfolioProject.addEventListener("change", () => {
      updateWizardSteps()
    })
  }
  
  if (wizardProjectType) {
    wizardProjectType.addEventListener("change", () => {
      updateWizardSteps()
    })
  }

  // Close wizard
  if (closeWizardBtn) {
    closeWizardBtn.addEventListener("click", () => {
      closeWizard()
    })
  }

  if (quoteWizardModal) {
    quoteWizardModal.addEventListener("click", (e) => {
      if (e.target === quoteWizardModal || e.target.classList.contains("wizard-overlay")) {
        closeWizard()
      }
    })
  }

  // Next step
  if (wizardNextBtn) {
    wizardNextBtn.addEventListener("click", () => {
      if (validateCurrentStep()) {
        currentStep++
        updateWizardSteps()
      }
    })
  }

  // Previous step
  if (wizardPrevBtn) {
    wizardPrevBtn.addEventListener("click", () => {
      currentStep--
      updateWizardSteps()
    })
  }

  // Initial step update
  updateWizardSteps()
}

// Export functions needed by forms.js
function getWizardFunctions() {
  return {
    isBathroomProject,
    isKitchenProject,
    calculateBathroomCategory,
    calculateKitchenCategory,
    validateCurrentStep,
    closeWizard,
    getCurrentStep: () => currentStep,
    getTotalSteps: () => totalSteps
  }
}

function getWizardForm() {
  return quoteWizardForm
}
