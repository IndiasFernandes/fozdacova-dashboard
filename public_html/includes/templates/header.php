<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? APP_NAME) ?></title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="dashboard-header">
        <div class="header-content">
            <div class="header-left">
                <h1 class="logo">Foz da Cova</h1>
                <?php if (isLoggedIn()): ?>
                    <span class="user-role"><?= e(ucfirst(getCurrentUserRole())) ?></span>
                <?php endif; ?>
            </div>
            
            <?php if (isLoggedIn()): ?>
                <div class="header-right">
                    <div class="user-info">
                        <div class="user-avatar">
                            <?= e($_SESSION['avatar_initials'] ?? 'U') ?>
                        </div>
                        <div class="user-details">
                            <span class="user-name"><?= e($_SESSION['first_name'] . ' ' . $_SESSION['last_name']) ?></span>
                            <a href="/logout.php" class="logout-link">Logout</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </header>
    
    <main class="dashboard-main">
        <?php if (isLoggedIn()): ?>
            <div class="dashboard-layout">
                <aside class="dashboard-sidebar">
                    <?php include 'sidebar-' . getCurrentUserRole() . '.php'; ?>
                </aside>
                <div class="dashboard-content">
        <?php endif; ?> 