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
                <span class="nav-text">Join Missions</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="activities.php" class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'activities.php') !== false ? 'active' : '' ?>">
                <span class="nav-icon">📝</span>
                <span class="nav-text">Log Activities</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="suggestions.php" class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'suggestions.php') !== false ? 'active' : '' ?>">
                <span class="nav-icon">💡</span>
                <span class="nav-text">Submit Ideas</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="learning.php" class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'learning.php') !== false ? 'active' : '' ?>">
                <span class="nav-icon">📚</span>
                <span class="nav-text">Learning</span>
            </a>
        </li>
    </ul>
</nav> 