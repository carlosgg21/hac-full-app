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
    ?>
    <link rel="stylesheet" href="<?= $fontsPath ?>">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="<?= $iconsPath ?>">
    
    <!-- ApexCharts CDN -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    
    <style>
        body, html { height: 100%; }
        
        /* Responsive sidebar collapse */
        @media (max-width: 1023px) {
            .sidebar {
                position: fixed;
                left: -260px;
                top: 4rem;
                width: 16rem;
                height: calc(100vh - 4rem);
                z-index: 50;
                background: #1e3a5f;
                border-right: 1px solid #1e3a5f;
                transition: left 0.25s;
            }
            .sidebar.open {
                left: 0;
            }
            .main-content {
                margin-left: 0 !important;
                width: 100%;
            }
            .sidebar-backdrop {
                display: block;
            }
        }
        
        @media (min-width: 1024px) {
            .sidebar {
                position: static;
                width: 16rem;
                z-index: 20;
                background: #1e3a5f;
                border-right: 1px solid #1e3a5f;
            }
            .sidebar-backdrop {
                display: none;
            }
        }
        
        .sidebar-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(31, 41, 55, 0.25);
            z-index: 40;
            display: none;
        }
        
        .main-content {
            padding: 1.5rem;
        }
        
        @media (max-width: 1023px) {
            .main-content {
                padding: 1.5rem;
            }
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
            <!-- Mensajes de sesión -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            
            <!-- Contenido de la vista -->
            <?= $content ?? '' ?>
        </main>
    </div>
    
    <!-- JavaScript para sidebar toggle -->
    <script>
        // RESPONSIVE SIDEBAR
        const sidebar = document.getElementById('mainSidebar');
        const sidebarBtn = document.getElementById('sidebarToggle');
        const sidebarBackdrop = document.getElementById('sidebarBackdrop');
        
        if(sidebarBtn) {
            sidebarBtn.addEventListener('click', () => {
                sidebar.classList.toggle('open');
                if (sidebarBackdrop) {
                    sidebarBackdrop.style.display = sidebar.classList.contains("open") ? 'block' : 'none';
                }
            });
        }
        
        if(sidebarBackdrop){
            sidebarBackdrop.addEventListener('click', () => {
                sidebar.classList.remove('open');
                sidebarBackdrop.style.display = 'none';
            });
        }
    </script>
</body>
</html>
