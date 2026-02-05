/**
 * Notify - Toasts reutilizables (sin librería)
 * Uso: Notify.success('Mensaje'), Notify.error('Mensaje')
 */
const Notify = {
  container: null,
  durationSuccess: 5000,
  durationError: 8000,

  getContainer() {
    if (!this.container) {
      this.container = document.createElement('div')
      this.container.id = 'notify-container'
      this.container.setAttribute('aria-live', 'polite')
      this.container.className = 'fixed bottom-4 right-4 z-[9999] flex flex-col gap-3 max-w-sm w-full pointer-events-none'
      document.body.appendChild(this.container)
    }
    return this.container
  },

  show(message, type = 'success') {
    const isSuccess = type === 'success'
    const toast = document.createElement('div')
    toast.className = 'notify-toast pointer-events-auto shadow-lg rounded-lg border overflow-hidden transition-all duration-300 ' +
      (isSuccess
        ? 'bg-white border-green-200'
        : 'bg-white border-red-200')
    toast.setAttribute('role', 'alert')

    const icon = isSuccess
      ? '<svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>'
      : '<svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'

    const closeBtn = '<button type="button" class="notify-close ml-2 p-1 rounded hover:bg-gray-100 text-gray-500 hover:text-gray-700 transition-colors" aria-label="Close"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>'

    toast.innerHTML = `
      <div class="flex items-start gap-3 p-4">
        <div class="flex-shrink-0 mt-0.5">${icon}</div>
        <p class="flex-1 text-sm text-gray-800 leading-snug">${escapeHtml(message)}</p>
        ${closeBtn}
      </div>
    `

    const close = () => {
      toast.style.transform = 'translateX(100%)'
      toast.style.opacity = '0'
      setTimeout(() => toast.remove(), 300)
    }

    toast.querySelector('.notify-close').addEventListener('click', close)

    const duration = isSuccess ? this.durationSuccess : this.durationError
    setTimeout(close, duration)

    toast.style.transform = 'translateX(100%)'
    toast.style.opacity = '0'
    this.getContainer().appendChild(toast)
    requestAnimationFrame(() => {
      toast.style.transform = 'translateX(0)'
      toast.style.opacity = '1'
    })
  },

  success(message) {
    this.show(message, 'success')
  },

  error(message) {
    this.show(message, 'error')
  }
}

function escapeHtml(s) {
  const d = document.createElement('div')
  d.textContent = s
  return d.innerHTML
}

window.Notify = Notify
