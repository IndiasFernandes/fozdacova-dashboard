<nav class="sidebar-nav">
    <ul class="nav-list">
        <li class="nav-item">
            <a href="/admin/dashboard.php" class="nav-link <?= strpos($_SERVER['PHP_SELF'], '/admin/dashboard.php') !== false ? 'active' : '' ?>">
                <span class="nav-icon">📊</span>
                <span class="nav-text">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="/admin/projects.php" class="nav-link <?= strpos($_SERVER['PHP_SELF'], '/admin/projects.php') !== false ? 'active' : '' ?>">
                <span class="nav-icon">🏗️</span>
                <span class="nav-text">Projects</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="/admin/expenses.php" class="nav-link <?= strpos($_SERVER['PHP_SELF'], '/admin/expenses.php') !== false ? 'active' : '' ?>">
                <span class="nav-icon">💰</span>
                <span class="nav-text">Expenses</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="/admin/suggestions.php" class="nav-link <?= strpos($_SERVER['PHP_SELF'], '/admin/suggestions.php') !== false ? 'active' : '' ?>">
                <span class="nav-icon">💡</span>
                <span class="nav-text">Suggestions</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="/admin/users.php" class="nav-link <?= strpos($_SERVER['PHP_SELF'], '/admin/users.php') !== false ? 'active' : '' ?>">
                <span class="nav-icon">👥</span>
                <span class="nav-text">Users</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="/admin/knowledge.php" class="nav-link <?= strpos($_SERVER['PHP_SELF'], '/admin/knowledge.php') !== false ? 'active' : '' ?>">
                <span class="nav-icon">📚</span>
                <span class="nav-text">Knowledge</span>
            </a>
        </li>
    </ul>
</nav> 