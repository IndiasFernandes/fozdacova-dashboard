<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/db.php';

// Require admin role
requireRole('admin');

$pageTitle = 'Projects - Admin';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create') {
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $category = $_POST['category'] ?? '';
        $status = $_POST['status'] ?? 'open';
        $priority = $_POST['priority'] ?? 'normal';
        $maxParticipants = $_POST['max_participants'] ?? 0;
        $location = $_POST['location'] ?? '';
        $startDate = $_POST['start_date'] ?? '';
        $endDate = $_POST['end_date'] ?? '';
        
        if (!empty($title)) {
            createProject($title, $description, $category, $status, $priority, $maxParticipants, $location, $startDate, $endDate, $_SESSION['user_id']);
            redirect('/admin/projects.php');
        }
    }
}

// Get projects
$statusFilter = $_GET['status'] ?? null;
$categoryFilter = $_GET['category'] ?? null;
$projects = getProjects($statusFilter, $categoryFilter);

include '../includes/templates/header.php';
?>

<div class="card">
    <div class="card-header">
        <div class="flex justify-between items-center">
            <h2 class="card-title">Projects</h2>
            <button class="btn btn-primary" onclick="document.getElementById('createProjectModal').style.display='block'">
                + New Project
            </button>
        </div>
    </div>
    <div class="card-body">
        <!-- Filters -->
        <div class="mb-6">
            <form method="GET" class="flex gap-4">
                <div class="form-group">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="open" <?= $statusFilter === 'open' ? 'selected' : '' ?>>Open</option>
                        <option value="in-progress" <?= $statusFilter === 'in-progress' ? 'selected' : '' ?>>In Progress</option>
                        <option value="completed" <?= $statusFilter === 'completed' ? 'selected' : '' ?>>Completed</option>
                        <option value="cancelled" <?= $statusFilter === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="category" class="form-label">Category</label>
                    <select name="category" id="category" class="form-select">
                        <option value="">All Categories</option>
                        <option value="water-systems" <?= $categoryFilter === 'water-systems' ? 'selected' : '' ?>>Water Systems</option>
                        <option value="natural-building" <?= $categoryFilter === 'natural-building' ? 'selected' : '' ?>>Natural Building</option>
                        <option value="gardening" <?= $categoryFilter === 'gardening' ? 'selected' : '' ?>>Gardening</option>
                        <option value="land-maintenance" <?= $categoryFilter === 'land-maintenance' ? 'selected' : '' ?>>Land Maintenance</option>
                        <option value="housekeeping" <?= $categoryFilter === 'housekeeping' ? 'selected' : '' ?>>Housekeeping</option>
                        <option value="community" <?= $categoryFilter === 'community' ? 'selected' : '' ?>>Community</option>
                        <option value="education" <?= $categoryFilter === 'education' ? 'selected' : '' ?>>Education</option>
                        <option value="sustainability" <?= $categoryFilter === 'sustainability' ? 'selected' : '' ?>>Sustainability</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-secondary">Filter</button>
                </div>
            </form>
        </div>

        <!-- Projects Table -->
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Participants</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($projects as $project): ?>
                    <tr>
                        <td>
                            <strong><?= e($project['title']) ?></strong>
                            <?php if ($project['description']): ?>
                                <br><small class="text-secondary"><?= e(substr($project['description'], 0, 50)) ?>...</small>
                            <?php endif; ?>
                        </td>
                        <td><?= e(ucfirst(str_replace('-', ' ', $project['category']))) ?></td>
                        <td>
                            <span class="status-badge status-<?= $project['status'] ?>">
                                <?= e(ucfirst(str_replace('-', ' ', $project['status']))) ?>
                            </span>
                        </td>
                        <td>
                            <span class="status-badge status-<?= $project['priority'] ?>">
                                <?= e(ucfirst($project['priority'])) ?>
                            </span>
                        </td>
                        <td><?= $project['current_participants'] ?>/<?= $project['max_participants'] ?: '∞' ?></td>
                        <td><?= date('M j, Y', strtotime($project['created_at'])) ?></td>
                        <td>
                            <a href="/admin/project-detail.php?id=<?= $project['id'] ?>" class="btn btn-sm btn-outline">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Create Project Modal -->
<div id="createProjectModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Create New Project</h3>
            <span class="close" onclick="document.getElementById('createProjectModal').style.display='none'">&times;</span>
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="create">
            
            <div class="form-group">
                <label for="title" class="form-label">Title</label>
                <input type="text" id="title" name="title" class="form-input" required>
            </div>
            
            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" name="description" class="form-textarea"></textarea>
            </div>
            
            <div class="form-group">
                <label for="category" class="form-label">Category</label>
                <select id="category" name="category" class="form-select" required>
                    <option value="">Select Category</option>
                    <option value="water-systems">Water Systems</option>
                    <option value="natural-building">Natural Building</option>
                    <option value="gardening">Gardening</option>
                    <option value="land-maintenance">Land Maintenance</option>
                    <option value="housekeeping">Housekeeping</option>
                    <option value="community">Community</option>
                    <option value="education">Education</option>
                    <option value="sustainability">Sustainability</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-select" required>
                    <option value="open">Open</option>
                    <option value="in-progress">In Progress</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="priority" class="form-label">Priority</label>
                <select id="priority" name="priority" class="form-select" required>
                    <option value="low">Low</option>
                    <option value="normal" selected>Normal</option>
                    <option value="high">High</option>
                    <option value="urgent">Urgent</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="max_participants" class="form-label">Max Participants</label>
                <input type="number" id="max_participants" name="max_participants" class="form-input" min="0">
            </div>
            
            <div class="form-group">
                <label for="location" class="form-label">Location</label>
                <input type="text" id="location" name="location" class="form-input">
            </div>
            
            <div class="form-group">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" id="start_date" name="start_date" class="form-input">
            </div>
            
            <div class="form-group">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" id="end_date" name="end_date" class="form-input">
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Create Project</button>
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('createProjectModal').style.display='none'">Cancel</button>
            </div>
        </form>
    </div>
</div>

<style>
.modal {
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.modal-content {
    background-color: var(--neutral-white);
    margin: 5% auto;
    padding: var(--space-6);
    border-radius: 8px;
    width: 90%;
    max-width: 600px;
    max-height: 80vh;
    overflow-y: auto;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--space-6);
}

.close {
    color: var(--text-secondary);
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover {
    color: var(--text-primary);
}

.form-actions {
    display: flex;
    gap: var(--space-4);
    margin-top: var(--space-6);
}
</style>

<?php include '../includes/templates/footer.php'; ?> 