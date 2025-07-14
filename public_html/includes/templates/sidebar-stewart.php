<nav class="sidebar-nav">
    <ul class="nav-list">
        <li class="nav-item">
            <a href="/stewart/dashboard.php" class="nav-link <?= strpos($_SERVER['PHP_SELF'], '/stewart/dashboard.php') !== false ? 'active' : '' ?>">
                <span class="nav-icon">📊</span>
                <span class="nav-text">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="/stewart/projects.php" class="nav-link <?= strpos($_SERVER['PHP_SELF'], '/stewart/projects.php') !== false ? 'active' : '' ?>">
                <span class="nav-icon">🏗️</span>
                <span class="nav-text">Projects</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="/stewart/costs.php" class="nav-link <?= strpos($_SERVER['PHP_SELF'], '/stewart/costs.php') !== false ? 'active' : '' ?>">
                <span class="nav-icon">💰</span>
                <span class="nav-text">Resource Flow</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="/stewart/suggestions.php" class="nav-link <?= strpos($_SERVER['PHP_SELF'], '/stewart/suggestions.php') !== false ? 'active' : '' ?>">
                <span class="nav-icon">💡</span>
                <span class="nav-text">Suggestions</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="/stewart/resources.php" class="nav-link <?= strpos($_SERVER['PHP_SELF'], '/stewart/resources.php') !== false ? 'active' : '' ?>">
                <span class="nav-icon">📚</span>
                <span class="nav-text">Resources</span>
            </a>
        </li>
    </ul>
</nav> 