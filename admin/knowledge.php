<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/db.php';

// Check if user is logged in and is admin
if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$user = $_SESSION['user'];

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $db = getDB();
        
        switch ($_POST['action']) {
            case 'add_guide':
                $title = trim($_POST['title']);
                $description = trim($_POST['description']);
                $category = $_POST['category'];
                $content = trim($_POST['content']);
                
                if (!empty($title) && !empty($content)) {
                    $stmt = $db->prepare("
                        INSERT INTO learning_resources (title, description, category, resource_type, url, created_by)
                        VALUES (?, ?, ?, 'document', ?, ?)
                    ");
                    $stmt->bindValue(1, $title);
                    $stmt->bindValue(2, $description);
                    $stmt->bindValue(3, $category);
                    $stmt->bindValue(4, $content); // Store content in url field for simplicity
                    $stmt->bindValue(5, $user['id'], SQLITE3_INTEGER);
                    $stmt->execute();
                }
                break;
        }
    }
}

// Get knowledge resources
$category_filter = $_GET['category'] ?? 'all';

$db = getDB();
$where_clause = $category_filter !== 'all' ? "WHERE category = ?" : "";

$query = "
    SELECT lr.*, u.first_name, u.last_name
    FROM learning_resources lr
    LEFT JOIN users u ON lr.created_by = u.id
    WHERE lr.resource_type = 'document'
    $where_clause
    ORDER BY lr.created_at DESC
";

$stmt = $db->prepare($query);
if ($category_filter !== 'all') {
    $stmt->bindValue(1, $category_filter);
}

$result = $stmt->execute();
$guides = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $guides[] = $row;
}

// Get summary stats
$stats_query = "
    SELECT 
        COUNT(*) as total_guides,
        COUNT(DISTINCT category) as categories,
        SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_guides
    FROM learning_resources 
    WHERE resource_type = 'document'
";
$stats_result = $db->query($stats_query);
$stats = $stats_result->fetchArray(SQLITE3_ASSOC);

$page_title = "Knowledge Base";
include '../includes/templates/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../includes/templates/sidebar-admin.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Knowledge Base</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addGuideModal">
                            <i class="bi bi-plus"></i> Add Guide
                        </button>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card text-white bg-primary">
                        <div class="card-body text-center">
                            <h5 class="card-title">Total Guides</h5>
                            <h3><?= $stats['total_guides'] ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-success">
                        <div class="card-body text-center">
                            <h5 class="card-title">Active Guides</h5>
                            <h3><?= $stats['active_guides'] ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-info">
                        <div class="card-body text-center">
                            <h5 class="card-title">Categories</h5>
                            <h3><?= $stats['categories'] ?></h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <form method="GET" class="row g-3">
                        <div class="col-md-6">
                            <select name="category" class="form-select">
                                <option value="all" <?= $category_filter === 'all' ? 'selected' : '' ?>>All Categories</option>
                                <option value="guides" <?= $category_filter === 'guides' ? 'selected' : '' ?>>Guides</option>
                                <option value="contacts" <?= $category_filter === 'contacts' ? 'selected' : '' ?>>Contacts</option>
                                <option value="maps" <?= $category_filter === 'maps' ? 'selected' : '' ?>>Maps</option>
                                <option value="notion" <?= $category_filter === 'notion' ? 'selected' : '' ?>>Notion</option>
                                <option value="other" <?= $category_filter === 'other' ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="knowledge.php" class="btn btn-secondary">Clear</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Guides List -->
            <div class="row">
                <?php foreach ($guides as $guide): ?>
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><?= htmlspecialchars($guide['title']) ?></h6>
                            <span class="badge bg-<?= $guide['is_active'] ? 'success' : 'secondary' ?>">
                                <?= $guide['is_active'] ? 'Active' : 'Inactive' ?>
                            </span>
                        </div>
                        <div class="card-body">
                            <p class="card-text"><?= htmlspecialchars($guide['description']) ?></p>
                            
                            <div class="mb-2">
                                <span class="badge bg-primary"><?= ucfirst($guide['category']) ?></span>
                            </div>
                            
                            <div class="mb-2">
                                <small class="text-muted">
                                    Created by <?= htmlspecialchars($guide['first_name'] . ' ' . $guide['last_name']) ?>
                                    on <?= date('M j, Y', strtotime($guide['created_at'])) ?>
                                </small>
                            </div>
                            
                            <div class="mt-auto">
                                <button class="btn btn-sm btn-outline-primary" onclick="viewGuide(<?= $guide['id'] ?>, '<?= htmlspecialchars($guide['title']) ?>', '<?= htmlspecialchars($guide['url']) ?>')">
                                    <i class="bi bi-eye"></i> View Guide
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <?php if (empty($guides)): ?>
            <div class="text-center py-5">
                <i class="bi bi-journal-text display-1 text-muted"></i>
                <h3 class="mt-3">No guides found</h3>
                <p class="text-muted">No guides match your current filters.</p>
            </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<!-- Add Guide Modal -->
<div class="modal fade" id="addGuideModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Guide</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add_guide">
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Title *</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select" id="category" name="category" required>
                            <option value="guides">Guides</option>
                            <option value="contacts">Contacts</option>
                            <option value="maps">Maps</option>
                            <option value="notion">Notion</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="content" class="form-label">Content *</label>
                        <textarea class="form-control" id="content" name="content" rows="10" placeholder="Write your guide content here..."></textarea>
                        <div class="form-text">You can use basic HTML formatting.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Guide</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Guide Modal -->
<div class="modal fade" id="viewGuideModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="guideTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="guideContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function viewGuide(guideId, title, content) {
    document.getElementById('guideTitle').textContent = title;
    document.getElementById('guideContent').innerHTML = content;
    
    const modal = new bootstrap.Modal(document.getElementById('viewGuideModal'));
    modal.show();
}
</script>

<?php include '../includes/templates/footer.php'; ?> 