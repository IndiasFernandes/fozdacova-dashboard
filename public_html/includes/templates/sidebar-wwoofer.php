<nav class="sidebar-nav">
    <ul class="nav-list">
        <li class="nav-item">
            <a href="/wwoofer/dashboard.php" class="nav-link <?= strpos($_SERVER['PHP_SELF'], '/wwoofer/dashboard.php') !== false ? 'active' : '' ?>">
                <span class="nav-icon">📊</span>
                <span class="nav-text">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="/wwoofer/projects.php" class="nav-link <?= strpos($_SERVER['PHP_SELF'], '/wwoofer/projects.php') !== false ? 'active' : '' ?>">
                <span class="nav-icon">🏗️</span>
                <span class="nav-text">Join Missions</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="/wwoofer/activities.php" class="nav-link <?= strpos($_SERVER['PHP_SELF'], '/wwoofer/activities.php') !== false ? 'active' : '' ?>">
                <span class="nav-icon">📝</span>
                <span class="nav-text">Log Activities</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="/wwoofer/suggestions.php" class="nav-link <?= strpos($_SERVER['PHP_SELF'], '/wwoofer/suggestions.php') !== false ? 'active' : '' ?>">
                <span class="nav-icon">💡</span>
                <span class="nav-text">Submit Ideas</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="/wwoofer/learning.php" class="nav-link <?= strpos($_SERVER['PHP_SELF'], '/wwoofer/learning.php') !== false ? 'active' : '' ?>">
                <span class="nav-icon">📚</span>
                <span class="nav-text">Learning</span>
            </a>
        </li>
    </ul>
</nav> 