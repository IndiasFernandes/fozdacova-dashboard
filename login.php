<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

// Redirect if already logged in
if (isLoggedIn()) {
    $role = getCurrentUserRole();
    redirect("/$role/dashboard.php");
}

$error = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        if (login($username, $password)) {
            $role = getCurrentUserRole();
            redirect("/$role/dashboard.php");
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-earth) 100%);
            padding: var(--space-4);
        }
        
        .login-card {
            background: var(--neutral-white);
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            padding: var(--space-8);
            width: 100%;
            max-width: 400px;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: var(--space-8);
        }
        
        .login-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-green);
            margin-bottom: var(--space-2);
        }
        
        .login-subtitle {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }
        
        .error-message {
            background: var(--accent-danger);
            color: var(--neutral-white);
            padding: var(--space-3);
            border-radius: 6px;
            margin-bottom: var(--space-4);
            font-size: 0.875rem;
        }
        
        .login-form {
            display: flex;
            flex-direction: column;
            gap: var(--space-4);
        }
        
        .login-button {
            background: var(--primary-green);
            color: var(--neutral-white);
            padding: var(--space-4);
            border: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.2s ease;
        }
        
        .login-button:hover {
            background: var(--primary-green-dark);
        }
        
        .demo-credentials {
            margin-top: var(--space-6);
            padding: var(--space-4);
            background: var(--background-secondary);
            border-radius: 6px;
            font-size: 0.875rem;
        }
        
        .demo-credentials h3 {
            margin-bottom: var(--space-3);
            color: var(--text-primary);
            font-size: 1rem;
        }
        
        .demo-credentials ul {
            list-style: none;
            padding: 0;
        }
        
        .demo-credentials li {
            margin-bottom: var(--space-2);
            color: var(--text-secondary);
        }
        
        .demo-credentials strong {
            color: var(--text-primary);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1 class="login-title">Foz da Cova</h1>
                <p class="login-subtitle">Community Dashboard</p>
            </div>
            
            <?php if ($error): ?>
                <div class="error-message"><?= e($error) ?></div>
            <?php endif; ?>
            
            <form method="POST" class="login-form">
                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" name="username" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-input" required>
                </div>
                
                <button type="submit" class="login-button">Sign In</button>
            </form>
            
            <div class="demo-credentials">
                <h3>Demo Credentials</h3>
                <ul>
                    <li><strong>Admin:</strong> admin / admin123</li>
                    <li><strong>Stewart:</strong> maria / maria123</li>
                    <li><strong>WWOOFer:</strong> joao / joao123</li>
                    <li><strong>WWOOFer:</strong> ana / ana123</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html> 