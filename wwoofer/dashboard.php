<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/db.php';

// Require wwoofer role
requireRole('wwoofer');

$pageTitle = 'WWOOFer Dashboard';

// Get dashboard statistics
$stats = getDashboardStats('wwoofer');

// Get user's activities
$userActivities = getActivities($_SESSION['user_id'], null, 5);

include '../includes/templates/header.php';
?>

<div class="dashboard-grid">
    <a href="/wwoofer/projects.php" class="dashboard-square">
        <div class="square-header">
            <h3 class="square-title">Join a Mission</h3>
            <span class="square-icon">🏗️</span>
        </div>
        <div class="square-stats">
            <div class="stat-item">
                <span class="stat-number"><?= $stats['total_projects'] ?></span>
                <span class="stat-label">Available</span>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?= count(array_filter($stats['recent_projects'], fn($p) => $p['status'] === 'open')) ?></span>
                <span class="stat-label">Open</span>
            </div>
        </div>
        <div class="square-action">Join missions →</div>
    </a>

    <a href="/wwoofer/activities.php" class="dashboard-square">
        <div class="square-header">
            <h3 class="square-title">Celebrate Your Impact</h3>
            <span class="square-icon">📝</span>
        </div>
        <div class="square-stats">
            <div class="stat-item">
                <span class="stat-number"><?= count($userActivities) ?></span>
                <span class="stat-label">Activities</span>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?= array_sum(array_column($userActivities, 'hours_worked')) ?></span>
                <span class="stat-label">Hours</span>
            </div>
        </div>
        <div class="square-action">Log activities →</div>
    </a>

    <a href="/wwoofer/suggestions.php" class="dashboard-square">
        <div class="square-header">
            <h3 class="square-title">Spark Ideas</h3>
            <span class="square-icon">💡</span>
        </div>
        <div class="square-stats">
            <div class="stat-item">
                <span class="stat-number"><?= $stats['total_suggestions'] ?></span>
                <span class="stat-label">Total</span>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?= count(array_filter($stats['recent_suggestions'], fn($s) => $s['submitted_by'] == $_SESSION['user_id'])) ?></span>
                <span class="stat-label">Yours</span>
            </div>
        </div>
        <div class="square-action">Submit ideas →</div>
    </a>

    <a href="/wwoofer/learning.php" class="dashboard-square">
        <div class="square-header">
            <h3 class="square-title">Grow & Explore</h3>
            <span class="square-icon">📚</span>
        </div>
        <div class="square-stats">
            <div class="stat-item">
                <span class="stat-number">4</span>
                <span class="stat-label">Resources</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">2</span>
                <span class="stat-label">Guides</span>
            </div>
        </div>
        <div class="square-action">Learn more →</div>
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Your Recent Activities</h2>
    </div>
    <div class="card-body">
        <?php if (!empty($userActivities)): ?>
            <?php foreach ($userActivities as $activity): ?>
                <div class="activity-item">
                    <div class="activity-icon">
                        <?php
                        $moodIcons = [
                            'great' => '😊',
                            'good' => '🙂',
                            'okay' => '😐',
                            'difficult' => '😔'
                        ];
                        echo $moodIcons[$activity['mood']] ?? '📝';
                        ?>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title"><?= e($activity['title']) ?></div>
                        <div class="activity-meta">
                            <?= $activity['hours_worked'] ?> hours • 
                            <?= e(ucfirst(str_replace('-', ' ', $activity['category']))) ?>
                            <?php if ($activity['project_title']): ?>
                                • <?= e($activity['project_title']) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="activity-time"><?= date('M j', strtotime($activity['created_at'])) ?></div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center text-secondary">No activities logged yet. Start contributing!</p>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/templates/footer.php'; ?> 