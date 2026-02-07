<?php $basePath = defined('BASE_PATH_URL') ? BASE_PATH_URL : '/backend'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - H.A.C. Renovation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { 'sans': ['Inter', 'sans-serif'] },
                    colors: {
                        'primary': '#1e3a5f',
                        'primary-light': '#2c4f7c',
                        'accent': '#e67e22',
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="<?= htmlspecialchars($basePath) ?>/public/assets/fonts/inter/fonts.css">
    <link rel="stylesheet" href="<?= htmlspecialchars($basePath) ?>/public/assets/icons/bootstrap-icons/bootstrap-icons.css">
    <style>
        body, html { height: 100%; }
        .input-focus-ring:focus-within { box-shadow: 0 0 0 2px #1e3a5f; }
        .input-with-icon { border: 1px solid #d1d5db; }
        .input-with-icon:focus-within { border-color: #1e3a5f; box-shadow: 0 0 0 2px rgba(30, 58, 95, 0.2); }
        .input-with-icon input { outline: none; }
        #loginLoader {
            position: fixed; inset: 0; z-index: 9999;
            background: #f3f4f6;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            gap: 1rem;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
        #loginLoader.hidden { opacity: 0; visibility: hidden; pointer-events: none; }
        .login-spinner {
            width: 48px; height: 48px;
            border: 4px solid #e5e7eb;
            border-top-color: #1e3a5f;
            border-radius: 50%;
            animation: login-spin 0.8s linear infinite;
        }
        .login-loader-text { font-size: 0.875rem; color: #6b7280; }
        @keyframes login-spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center font-sans antialiased">
    <div id="loginLoader" aria-live="polite" aria-label="Loading">
        <div class="login-spinner" aria-hidden="true"></div>
        <span class="login-loader-text">Loading...</span>
    </div>
    <div class="w-full max-w-md mx-4" id="loginContent">
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
            <h1 class="text-xl font-semibold text-primary text-center mb-1">H.A.C. Renovation</h1>
            <p class="text-gray-500 text-sm text-center mb-6">Enter your credentials to continue</p>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">
                    <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= Response::url('/login') ?>" class="space-y-4">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <div class="input-with-icon input-focus-ring flex items-center rounded-lg bg-white overflow-hidden min-h-[42px]">
                        <span class="flex items-center justify-center flex-shrink-0 w-10 text-gray-400 bi bi-person text-lg" aria-hidden="true"></span>
                        <input type="text" id="username" name="username" placeholder="Username" required autofocus
                            class="flex-1 min-w-0 py-2.5 pr-3 text-gray-900 placeholder-gray-400 border-0 focus:ring-0 bg-transparent">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="input-with-icon input-focus-ring flex items-center rounded-lg bg-white overflow-hidden min-h-[42px]">
                        <span class="flex items-center justify-center flex-shrink-0 w-10 text-gray-400 bi bi-lock text-lg" aria-hidden="true"></span>
                        <input type="password" id="password" name="password" placeholder="Password" required
                            class="flex-1 min-w-0 py-2.5 pr-2 text-gray-900 placeholder-gray-400 border-0 focus:ring-0 bg-transparent">
                        <button type="button" id="togglePassword" title="Show password" aria-label="Show or hide password"
                            class="flex items-center justify-center flex-shrink-0 w-10 h-full min-h-[42px] text-gray-400 hover:text-primary transition-colors">
                            <span class="bi bi-eye text-lg" id="togglePasswordIcon" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full py-2.5 px-4 bg-primary hover:bg-primary-light text-white font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                    Log in
                </button>
            </form>
        </div>
    </div>
    <script>
        (function() {
            function hideLoader() {
                var loader = document.getElementById('loginLoader');
                if (loader) loader.classList.add('hidden');
            }
            if (document.readyState === 'complete') {
                hideLoader();
            } else {
                window.addEventListener('load', hideLoader);
            }
            var btn = document.getElementById('togglePassword');
            var input = document.getElementById('password');
            var icon = document.getElementById('togglePasswordIcon');
            if (btn && input && icon) {
                btn.addEventListener('click', function() {
                    var isPassword = input.type === 'password';
                    input.type = isPassword ? 'text' : 'password';
                    icon.classList.toggle('bi-eye', isPassword);
                    icon.classList.toggle('bi-eye-slash', !isPassword);
                    btn.setAttribute('title', isPassword ? 'Hide password' : 'Show password');
                });
            }
        })();
    </script>
</body>
</html>
