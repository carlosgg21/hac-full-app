// Initialize UI components
function initUI() {
  // Load saved language preference or default to English
  const savedLang = localStorage.getItem("preferredLanguage")
  if (savedLang === "fr") {
    toggleLanguage()
  }

  // Language toggle button (desktop)
  const langToggle = document.getElementById("langToggle")
  if (langToggle) {
    langToggle.addEventListener("click", toggleLanguage)
  }

  // Language toggle button (mobile)
  const langToggleMobile = document.getElementById("langToggleMobile")
  if (langToggleMobile) {
    langToggleMobile.addEventListener("click", toggleLanguage)
  }

  // Header scroll effect
  const header = document.getElementById("header")
  const scrollToTopBtn = document.getElementById("scrollToTopBtn")
  
  if (header) {
    window.addEventListener("scroll", () => {
      if (window.scrollY > 20) {
        header.classList.add("scrolled")
      } else {
        header.classList.remove("scrolled")
      }
    })
  }
  
  // Show/hide scroll to top button
  if (scrollToTopBtn) {
    window.addEventListener("scroll", () => {
      if (window.scrollY > 300) {
        scrollToTopBtn.classList.add("visible")
      } else {
        scrollToTopBtn.classList.remove("visible")
      }
    })
    
    // Scroll to top functionality
    scrollToTopBtn.addEventListener("click", () => {
      window.scrollTo({
        top: 0,
        behavior: "smooth",
      })
    })
  }

  // Mobile menu toggle
  const mobileMenuBtn = document.getElementById("mobileMenuBtn")
  const mobileMenu = document.getElementById("mobileMenu")
  const menuIcon = document.getElementById("menuIcon")
  const closeIcon = document.getElementById("closeIcon")

  if (mobileMenuBtn && mobileMenu && menuIcon && closeIcon) {
    mobileMenuBtn.addEventListener("click", () => {
      mobileMenu.classList.toggle("hidden")
      menuIcon.classList.toggle("hidden")
      closeIcon.classList.toggle("hidden")
    })

    // Close mobile menu when clicking on links
    const mobileNavLinks = document.querySelectorAll(".mobile-nav-link")
    mobileNavLinks.forEach((link) => {
      link.addEventListener("click", () => {
        mobileMenu.classList.add("hidden")
        menuIcon.classList.remove("hidden")
        closeIcon.classList.add("hidden")
      })
    })
  }

  // Smooth scroll for anchor links
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault()
      const target = document.querySelector(this.getAttribute("href"))
      if (target) {
        const headerHeight = header ? header.offsetHeight : 0
        const targetPosition = target.offsetTop - headerHeight
        window.scrollTo({
          top: targetPosition,
          behavior: "smooth",
        })
      }
    })
  })
}
