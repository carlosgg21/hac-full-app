// Initialize forms
function initForms() {
  const quoteWizardForm = getWizardForm()
  if (!quoteWizardForm) {
    console.warn("Quote wizard form not found")
    return
  }

  const wizardFunctions = getWizardFunctions()
  const wizardSubmitBtn = document.getElementById("wizardSubmitBtn")

  // Submit form to Google Sheets
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
    
    // Validate current step before submitting
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
      created_at: new Date().toISOString(),
      status: "new"
    }

    // Add bathroom-specific, kitchen-specific, or default project data
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

    // Disable submit button
    if (wizardSubmitBtn) {
      wizardSubmitBtn.disabled = true
      wizardSubmitBtn.textContent = currentLanguage === "en" ? "Submitting..." : "Envoi en cours..."
    }

    try {
      // Google Apps Script URL
      const scriptURL = "https://script.google.com/macros/s/AKfycbxcIVolM9jPbe1_2avpIszdyA9efwO7Z_HApvVWg_cBXp5djGZDJlMdZkPLPHLG23g/exec"
      
      // Check if URL is still placeholder
      if (scriptURL === "YOUR_GOOGLE_APPS_SCRIPT_URL_HERE" || !scriptURL.includes("script.google.com")) {
        const configMessage = currentLanguage === "en"
          ? "⚠️ Google Sheets integration not configured yet.\n\nPlease:\n1. Open your Google Sheet\n2. Go to Extensions > Apps Script\n3. Deploy as Web App\n4. Copy the URL and paste it in js/forms.js\n\nFor now, your data:\n" + JSON.stringify(data, null, 2)
          : "⚠️ Intégration Google Sheets non configurée.\n\nVeuillez:\n1. Ouvrir votre Google Sheet\n2. Aller à Extensions > Apps Script\n3. Déployer comme Application Web\n4. Copier l'URL et la coller dans js/forms.js\n\nPour l'instant, vos données:\n" + JSON.stringify(data, null, 2)
        
        alert(configMessage)
        console.log("Form data:", data)
        if (wizardSubmitBtn) {
          wizardSubmitBtn.disabled = false
          wizardSubmitBtn.textContent = currentLanguage === "en" ? "Submit Quote" : "Soumettre le devis"
        }
        return
      }
      
      // Log data being sent for debugging
      console.log("Sending data to Google Sheets:", data)
      console.log("Data keys:", Object.keys(data))
      console.log("Data values:", Object.values(data))
      
      // Prepare URL-encoded form data (more compatible with Google Apps Script)
      const urlEncodedData = new URLSearchParams()
      Object.keys(data).forEach(key => {
        const value = data[key] || ''
        urlEncodedData.append(key, value)
        console.log(`Adding field: ${key} = ${value}`)
      })
      
      console.log("URL-encoded data:", urlEncodedData.toString())
      
      // Try with CORS first to see the response
      let response
      let success = false
      
      try {
        response = await fetch(scriptURL, {
          method: "POST",
          mode: "cors",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: urlEncodedData.toString()
        })
        
        if (response.ok) {
          const result = await response.json()
          console.log("Response from Google Sheets:", result)
          success = true
        } else {
          console.error("Error response:", response.status, response.statusText)
          // Try with no-cors as fallback
          throw new Error("CORS request failed, trying no-cors")
        }
      } catch (corsError) {
        console.log("CORS failed, trying no-cors mode:", corsError)
        // Fallback to no-cors mode
        try {
          await fetch(scriptURL, {
            method: "POST",
            mode: "no-cors",
            headers: {
              "Content-Type": "application/x-www-form-urlencoded",
            },
            body: urlEncodedData.toString()
          })
          console.log("Request sent in no-cors mode (cannot verify response)")
          // With no-cors, we can't verify success, so we assume it worked
          success = true
        } catch (noCorsError) {
          console.error("Both CORS and no-cors failed:", noCorsError)
          throw noCorsError
        }
      }
      
      if (success) {
        // Wait a moment to ensure the request is processed
        await new Promise(resolve => setTimeout(resolve, 500))
        
        // Show success message
        const successMessage = currentLanguage === "en"
          ? "Thank you! Your quote request has been submitted. We will contact you soon."
          : "Merci! Votre demande de devis a été soumise. Nous vous contacterons bientôt."
        
        alert(successMessage)
      } else {
        throw new Error("Failed to submit form")
      }
      
      // Reset forms
      quoteWizardForm.reset()
      const contactForm = document.getElementById("contactForm")
      if (contactForm) {
        contactForm.reset()
      }
      closeWizard()
      
    } catch (error) {
      console.error("Error:", error)
      console.error("Error details:", error.message)
      const errorMessage = currentLanguage === "en"
        ? "There was an error submitting your request. Please try again or contact us directly. Check the console for details."
        : "Une erreur s'est produite lors de l'envoi de votre demande. Veuillez réessayer ou nous contacter directement. Vérifiez la console pour plus de détails."
      alert(errorMessage)
    } finally {
      if (wizardSubmitBtn) {
        wizardSubmitBtn.disabled = false
        wizardSubmitBtn.textContent = currentLanguage === "en" ? "Submit Quote" : "Soumettre le devis"
      }
    }
  })
}
