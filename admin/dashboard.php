<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/db.php';

// Require admin role
requireRole('admin');

$pageTitle = 'Admin Dashboard';

// Get dashboard statistics
$stats = getDashboardStats('admin');

include '../includes/templates/header.php';
?>

<div class="dashboard-grid">
    <a href="/admin/projects.php" class="dashboard-square">
        <div class="square-header">
            <h3 class="square-title">Projects</h3>
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
        <div class="square-action">View all projects →</div>
    </a>

    <a href="/admin/expenses.php" class="dashboard-square">
        <div class="square-header">
            <h3 class="square-title">Expenses</h3>
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
        <div class="square-action">View all expenses →</div>
    </a>

    <a href="/admin/suggestions.php" class="dashboard-square">
        <div class="square-header">
            <h3 class="square-title">Suggestions</h3>
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

    <a href="/admin/users.php" class="dashboard-square">
        <div class="square-header">
            <h3 class="square-title">Users</h3>
            <span class="square-icon">👥</span>
        </div>
        <div class="square-stats">
            <div class="stat-item">
                <span class="stat-number">4</span>
                <span class="stat-label">Total</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">2</span>
                <span class="stat-label">WWOOFers</span>
            </div>
        </div>
        <div class="square-action">Manage users →</div>
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