<nav class="sidebar-nav">
    <ul class="nav-list">
        <li class="nav-item">
            <a href="dashboard.php" class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'dashboard.php') !== false ? 'active' : '' ?>">
                <span class="nav-icon">📊</span>
                <span class="nav-text">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="projects.php" class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'projects.php') !== false ? 'active' : '' ?>">
                <span class="nav-icon">🏗️</span>
                <span class="nav-text">Projects</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="expenses.php" class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'expenses.php') !== false ? 'active' : '' ?>">
                <span class="nav-icon">💰</span>
                <span class="nav-text">Expenses</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="budget.php" class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'budget.php') !== false ? 'active' : '' ?>">
                <span class="nav-icon">📈</span>
                <span class="nav-text">Budget</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="ideas.php" class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'ideas.php') !== false ? 'active' : '' ?>">
                <span class="nav-icon">💡</span>
                <span class="nav-text">Ideas & Suggestions</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="resources.php" class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'resources.php') !== false ? 'active' : '' ?>">
                <span class="nav-icon">📚</span>
                <span class="nav-text">Resources</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="knowledge.php" class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'knowledge.php') !== false ? 'active' : '' ?>">
                <span class="nav-icon">📖</span>
                <span class="nav-text">Knowledge Base</span>
            </a>
        </li>
    </ul>
</nav> 