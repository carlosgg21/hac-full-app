<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'H.A.C. Renovation - Admin' ?></title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Tailwind Config - Colores personalizados -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'sans-serif'],
                    },
                    colors: {
                        'primary': '#1e3a5f',
                        'primary-light': '#2c4f7c',
                        'accent': '#e67e22',
                        'accent-light': '#f39c12',
                    }
                }
            }
        }
    </script>
    
    <!-- Local Fonts - Inter -->
    <?php 
    $basePath = defined('BASE_PATH_URL') ? BASE_PATH_URL : '/backend';
    $fontsPath = $basePath . '/public/assets/fonts/inter/fonts.css';
    $iconsPath = $basePath . '/public/assets/icons/bootstrap-icons/bootstrap-icons.css';
    $layoutSuccessMessage = isset($_SESSION['success']) ? $_SESSION['success'] : null;
    if ($layoutSuccessMessage !== null) unset($_SESSION['success']);
    ?>
    <link rel="stylesheet" href="<?= $fontsPath ?>">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="<?= $iconsPath ?>">
    
    <!-- Toastify -->
    <link rel="stylesheet" href="<?= $basePath ?>/public/assets/node_module/toastify-js/toastify.css">
    
    <style>
        body, html { height: 100%; }
        
        /* Estilos base del sidebar */
        .sidebar {
            background: #1e3a5f;
            border-right: 1px solid #1e3a5f;
        }
        
        /* Desktop: > 1024px - Sidebar siempre visible */
        @media (min-width: 1025px) {
            .sidebar {
                position: static;
                width: 16rem;
                z-index: 20;
                transform: translateX(0) !important;
            }
            .sidebar-backdrop {
                display: none !important;
            }
            .sidebar-toggle {
                display: none !important;
            }
        }
        
        /* Tablet: 768px - 1024px - Sidebar oculto, hamburguesa visible */
        @media (min-width: 768px) and (max-width: 1024px) {
            .sidebar {
                position: fixed;
                left: 0;
                top: 4rem;
                width: 16rem;
                height: calc(100vh - 4rem);
                z-index: 50;
                background: #1e3a5f;
                border-right: 1px solid #1e3a5f;
                box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
                will-change: transform;
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0 !important;
                width: 100%;
            }
            .sidebar-backdrop {
                display: block;
            }
            .sidebar-toggle {
                display: block;
            }
        }
        
        /* Móvil: < 768px - Sidebar oculto, hamburguesa visible */
        @media (max-width: 767px) {
            .sidebar {
                position: fixed;
                left: 0;
                top: 4rem;
                width: 16rem;
                height: calc(100vh - 4rem);
                z-index: 50;
                background: #1e3a5f;
                border-right: 1px solid #1e3a5f;
                box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
                will-change: transform;
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0 !important;
                width: 100%;
            }
            .sidebar-backdrop {
                display: block;
            }
            .sidebar-toggle {
                display: block;
            }
        }
        
        /* Overlay/Backdrop mejorado con animación */
        .sidebar-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(31, 41, 55, 0.5);
            z-index: 40;
            display: none;
            opacity: 0;
            transition: opacity 0.2s ease-in-out;
        }
        
        .sidebar-backdrop.show {
            display: block;
            opacity: 1;
        }
        
        .main-content {
            padding: 1.5rem;
            transition: margin-left 0.3s ease-in-out;
        }
        
        /* Animación del botón hamburguesa */
        .hamburger-icon {
            width: 24px;
            height: 18px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            cursor: pointer;
        }
        
        .hamburger-line {
            width: 24px;
            height: 2px;
            background-color: #1e3a5f;
            border-radius: 2px;
            transition: all 0.3s ease-in-out;
            transform-origin: center;
        }
        
        .hamburger-icon.open .hamburger-line:nth-child(1) {
            transform: rotate(45deg) translate(6px, 6px);
        }
        
        .hamburger-icon.open .hamburger-line:nth-child(2) {
            opacity: 0;
            transform: scaleX(0);
        }
        
        .hamburger-icon.open .hamburger-line:nth-child(3) {
            transform: rotate(-45deg) translate(6px, -6px);
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <!-- Sidebar Backdrop (Mobile Only) -->
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>
    
    <!-- Include Navbar -->
    <?php include __DIR__ . '/includes/navbar.php'; ?>
    
    <!-- Layout Container -->
    <div class="min-h-screen flex pt-16 bg-gray-50">
        <!-- Include Sidebar -->
        <?php include __DIR__ . '/includes/sidebar.php'; ?>
        
        <!-- Main Content -->
        <main class="main-content flex-1 flex flex-col">
            <!-- Mensajes de sesión: success → toast al final del layout -->
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            
            <!-- Contenido de la vista -->
            <?= $content ?? '' ?>
        </main>
    </div>
    
    <!-- JavaScript para sidebar toggle mejorado -->
    <script>
        // RESPONSIVE SIDEBAR con mejoras
        const sidebar = document.getElementById('mainSidebar');
        const sidebarBtn = document.getElementById('sidebarToggle');
        const sidebarBackdrop = document.getElementById('sidebarBackdrop');
        const hamburgerIcon = document.getElementById('hamburgerIcon');
        
        // Función para abrir sidebar
        function openSidebar() {
            if (sidebar) {
                sidebar.classList.add('open');
                if (hamburgerIcon) {
                    hamburgerIcon.classList.add('open');
                }
                if (sidebarBtn) {
                    sidebarBtn.setAttribute('aria-expanded', 'true');
                }
                if (sidebarBackdrop) {
                    sidebarBackdrop.classList.add('show');
                }
                // Prevenir scroll del body cuando sidebar está abierto
                document.body.style.overflow = 'hidden';
            }
        }
        
        // Función para cerrar sidebar
        function closeSidebar() {
            if (sidebar) {
                sidebar.classList.remove('open');
                if (hamburgerIcon) {
                    hamburgerIcon.classList.remove('open');
                }
                if (sidebarBtn) {
                    sidebarBtn.setAttribute('aria-expanded', 'false');
                }
                if (sidebarBackdrop) {
                    sidebarBackdrop.classList.remove('show');
                    // Esperar a que termine la animación antes de ocultar
                    setTimeout(() => {
                        if (!sidebar.classList.contains('open')) {
                            sidebarBackdrop.style.display = 'none';
                        }
                    }, 200);
                }
                // Restaurar scroll del body
                document.body.style.overflow = '';
            }
        }
        
        // Toggle sidebar
        if(sidebarBtn) {
            sidebarBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                if (sidebar && sidebar.classList.contains('open')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            });
        }
        
        // Cerrar al hacer clic en backdrop
        if(sidebarBackdrop) {
            sidebarBackdrop.addEventListener('click', () => {
                closeSidebar();
            });
        }
        
        // Cerrar al hacer clic en enlaces del sidebar (solo en móvil/tablet)
        if (sidebar) {
            const sidebarLinks = sidebar.querySelectorAll('a');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', () => {
                    // Solo cerrar en pantallas pequeñas (donde el sidebar está oculto por defecto)
                    if (window.innerWidth <= 1024) {
                        closeSidebar();
                    }
                });
            });
        }
        
        // Cerrar con tecla ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && sidebar && sidebar.classList.contains('open')) {
                closeSidebar();
            }
        });
        
        // Cerrar al redimensionar ventana (si se pasa a desktop)
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                if (window.innerWidth > 1024 && sidebar && sidebar.classList.contains('open')) {
                    closeSidebar();
                }
            }, 250);
        });
        
        // Inicializar estado del backdrop
        if (sidebarBackdrop) {
            sidebarBackdrop.style.display = 'none';
        }
    </script>
    
    <!-- Toastify + appToast helper -->
    <script src="<?= $basePath ?>/public/assets/node_module/toastify-js/toastify.js"></script>
    <script>
        window.appToast = function(opts) {
            var type = (opts && opts.type) || 'info';
            var text = (opts && opts.text) || '';
            var colors = { success: '#10b981', error: '#dc2626', info: '#1e3a5f' };
            Toastify({
                text: text,
                duration: 4000,
                gravity: 'top',
                position: 'right',
                style: { background: colors[type] || colors.info }
            }).showToast();
        };
        <?php if (!empty($layoutSuccessMessage)): ?>
        appToast({ type: 'success', text: <?= json_encode($layoutSuccessMessage) ?> });
        <?php endif; ?>
    </script>
</body>
</html>
