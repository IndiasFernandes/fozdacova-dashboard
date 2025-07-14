<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/db.php';

// Require stewart role
requireRole('stewart');

$pageTitle = 'Stewart Dashboard';

// Get dashboard statistics
$stats = getDashboardStats('stewart');

include '../includes/templates/header.php';
?>

<div class="dashboard-grid">
    <a href="/stewart/projects.php" class="dashboard-square">
        <div class="square-header">
            <h3 class="square-title">Active Missions</h3>
            <span class="square-icon">🏗️</span>
        </div>
        <div class="square-stats">
            <div class="stat-item">
                <span class="stat-number"><?= $stats['total_projects'] ?></span>
                <span class="stat-label">Total</span>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?= count(array_filter($stats['recent_projects'], fn($p) => $p['status'] === 'in-progress')) ?></span>
                <span class="stat-label">Active</span>
            </div>
        </div>
        <div class="square-action">View all missions →</div>
    </a>

    <a href="/stewart/costs.php" class="dashboard-square">
        <div class="square-header">
            <h3 class="square-title">Resource Flow</h3>
            <span class="square-icon">💰</span>
        </div>
        <div class="square-stats">
            <div class="stat-item">
                <span class="stat-number"><?= $stats['total_expenses'] ?></span>
                <span class="stat-label">Total</span>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?= count(array_filter($stats['recent_expenses'], fn($e) => $e['status'] === 'pending')) ?></span>
                <span class="stat-label">Pending</span>
            </div>
        </div>
        <div class="square-action">View resource flow →</div>
    </a>

    <a href="/stewart/suggestions.php" class="dashboard-square">
        <div class="square-header">
            <h3 class="square-title">Dream Projects</h3>
            <span class="square-icon">💡</span>
        </div>
        <div class="square-stats">
            <div class="stat-item">
                <span class="stat-number"><?= $stats['total_suggestions'] ?></span>
                <span class="stat-label">Total</span>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?= count(array_filter($stats['recent_suggestions'], fn($s) => $s['status'] === 'new')) ?></span>
                <span class="stat-label">New</span>
            </div>
        </div>
        <div class="square-action">Review suggestions →</div>
    </a>

    <a href="/stewart/resources.php" class="dashboard-square">
        <div class="square-header">
            <h3 class="square-title">Resources</h3>
            <span class="square-icon">📚</span>
        </div>
        <div class="square-stats">
            <div class="stat-item">
                <span class="stat-number">4</span>
                <span class="stat-label">Available</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">2</span>
                <span class="stat-label">Guides</span>
            </div>
        </div>
        <div class="square-action">Access resources →</div>
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Recent Activities</h2>
    </div>
    <div class="card-body">
        <?php if (!empty($stats['recent_projects'])): ?>
            <?php foreach (array_slice($stats['recent_projects'], 0, 5) as $project): ?>
                <div class="activity-item">
                    <div class="activity-icon">🏗️</div>
                    <div class="activity-content">
                        <div class="activity-title"><?= e($project['title']) ?></div>
                        <div class="activity-meta">
                            Created by <?= e($project['first_name'] . ' ' . $project['last_name']) ?>
                            <span class="status-badge status-<?= $project['status'] ?>"><?= e(ucfirst(str_replace('-', ' ', $project['status']))) ?></span>
                        </div>
                    </div>
                    <div class="activity-time"><?= date('M j', strtotime($project['created_at'])) ?></div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center text-secondary">No recent activities</p>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/templates/footer.php'; ?> 