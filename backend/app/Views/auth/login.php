<?php $basePath = defined('BASE_PATH_URL') ? BASE_PATH_URL : '/backend'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - H.A.C. Renovation</title>
    <link rel="stylesheet" href="<?= htmlspecialchars($basePath) ?>/public/assets/icons/bootstrap-icons/bootstrap-icons.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .login-container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .login-container h1 {
            color: #1e3a5f;
            margin-bottom: 0.25rem;
            text-align: center;
            font-size: 1.5rem;
        }
        .login-container .subtitle {
            color: #666;
            font-size: 0.9rem;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
        }
        .input-wrap {
            display: flex;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: #fff;
        }
        .input-wrap:focus-within {
            border-color: #1e3a5f;
            outline: 1px solid #1e3a5f;
        }
        .input-wrap .input-icon {
            padding: 0 0.75rem;
            color: #666;
            font-size: 1.1rem;
        }
        .input-wrap input {
            flex: 1;
            border: none;
            padding: 0.75rem;
            font-size: 1rem;
            background: transparent;
        }
        .input-wrap input:focus {
            outline: none;
        }
        .input-wrap .toggle-password {
            padding: 0.5rem 0.75rem;
            border: none;
            background: none;
            color: #666;
            cursor: pointer;
            font-size: 1.1rem;
        }
        .input-wrap .toggle-password:hover {
            color: #1e3a5f;
        }
        .btn {
            width: 100%;
            padding: 0.75rem;
            background: #1e3a5f;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 0.5rem;
        }
        .btn:hover { background: #2a4d75; }
        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>H.A.C. Renovation</h1>
        <p class="subtitle">Enter your credentials to continue</p>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="<?= Response::url('/login') ?>">
            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-wrap">
                    <span class="input-icon bi bi-person" aria-hidden="true"></span>
                    <input type="text" id="username" name="username" placeholder="Username" required autofocus>
                </div>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <span class="input-icon bi bi-lock" aria-hidden="true"></span>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <button type="button" class="toggle-password" id="togglePassword" title="Show password" aria-label="Show or hide password">
                        <span class="bi bi-eye" id="togglePasswordIcon" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
            
            <button type="submit" class="btn">Login In</button>
        </form>
    </div>
    <script>
        (function() {
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