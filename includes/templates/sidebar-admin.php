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
            <a href="/admin/budget.php" class="nav-link <?= strpos($_SERVER['PHP_SELF'], '/admin/budget.php') !== false ? 'active' : '' ?>">
                <span class="nav-icon">📈</span>
                <span class="nav-text">Budget</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="/admin/ideas.php" class="nav-link <?= strpos($_SERVER['PHP_SELF'], '/admin/ideas.php') !== false ? 'active' : '' ?>">
                <span class="nav-icon">💡</span>
                <span class="nav-text">Ideas & Suggestions</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="/admin/resources.php" class="nav-link <?= strpos($_SERVER['PHP_SELF'], '/admin/resources.php') !== false ? 'active' : '' ?>">
                <span class="nav-icon">📚</span>
                <span class="nav-text">Resources</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="/admin/knowledge.php" class="nav-link <?= strpos($_SERVER['PHP_SELF'], '/admin/knowledge.php') !== false ? 'active' : '' ?>">
                <span class="nav-icon">📖</span>
                <span class="nav-text">Knowledge Base</span>
            </a>
        </li>
    </ul>
</nav> 