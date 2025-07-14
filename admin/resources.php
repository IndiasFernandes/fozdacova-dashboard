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
            case 'add_resource':
                $title = trim($_POST['title']);
                $description = trim($_POST['description']);
                $category = $_POST['category'];
                $resourceType = $_POST['resource_type'];
                $url = trim($_POST['url'] ?? '');
                $fileUrl = trim($_POST['file_url'] ?? '');
                
                if (!empty($title)) {
                    $stmt = $db->prepare("
                        INSERT INTO learning_resources (title, description, category, resource_type, url, file_url, created_by)
                        VALUES (?, ?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->bindValue(1, $title);
                    $stmt->bindValue(2, $description);
                    $stmt->bindValue(3, $category);
                    $stmt->bindValue(4, $resourceType);
                    $stmt->bindValue(5, $url);
                    $stmt->bindValue(6, $fileUrl);
                    $stmt->bindValue(7, $user['id'], SQLITE3_INTEGER);
                    $stmt->execute();
                }
                break;
                
            case 'toggle_active':
                $resourceId = (int)$_POST['resource_id'];
                $newStatus = $_POST['new_status'];
                
                $stmt = $db->prepare("UPDATE learning_resources SET is_active = ? WHERE id = ?");
                $stmt->bindValue(1, $newStatus);
                $stmt->bindValue(2, $resourceId, SQLITE3_INTEGER);
                $stmt->execute();
                break;
        }
    }
}

// Get resources with filters
$category_filter = $_GET['category'] ?? 'all';
$type_filter = $_GET['type'] ?? 'all';

$db = getDB();
$where_conditions = [];
$params = [];

if ($category_filter !== 'all') {
    $where_conditions[] = "category = ?";
    $params[] = $category_filter;
}

if ($type_filter !== 'all') {
    $where_conditions[] = "resource_type = ?";
    $params[] = $type_filter;
}

$where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

$query = "
    SELECT lr.*, u.first_name, u.last_name
    FROM learning_resources lr
    LEFT JOIN users u ON lr.created_by = u.id
    $where_clause
    ORDER BY lr.created_at DESC
";

$stmt = $db->prepare($query);
foreach ($params as $i => $param) {
    $stmt->bindValue($i + 1, $param);
}

$result = $stmt->execute();
$resources = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $resources[] = $row;
}

// Get summary stats
$stats_query = "
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active,
        SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) as inactive,
        COUNT(DISTINCT category) as categories
    FROM learning_resources
";
$stats_result = $db->query($stats_query);
$stats = $stats_result->fetchArray(SQLITE3_ASSOC);

$page_title = "Resources Management";
include '../includes/templates/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../includes/templates/sidebar-admin.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Resources Management</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addResourceModal">
                            <i class="bi bi-plus"></i> Add Resource
                        </button>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-white bg-primary">
                        <div class="card-body text-center">
                            <h5 class="card-title">Total Resources</h5>
                            <h3><?= $stats['total'] ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success">
                        <div class="card-body text-center">
                            <h5 class="card-title">Active</h5>
                            <h3><?= $stats['active'] ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-secondary">
                        <div class="card-body text-center">
                            <h5 class="card-title">Inactive</h5>
                            <h3><?= $stats['inactive'] ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
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
                <div class="col-md-8">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <select name="category" class="form-select">
                                <option value="all" <?= $category_filter === 'all' ? 'selected' : '' ?>>All Categories</option>
                                <option value="guides" <?= $category_filter === 'guides' ? 'selected' : '' ?>>Guides</option>
                                <option value="contacts" <?= $category_filter === 'contacts' ? 'selected' : '' ?>>Contacts</option>
                                <option value="maps" <?= $category_filter === 'maps' ? 'selected' : '' ?>>Maps</option>
                                <option value="notion" <?= $category_filter === 'notion' ? 'selected' : '' ?>>Notion</option>
                                <option value="other" <?= $category_filter === 'other' ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="type" class="form-select">
                                <option value="all" <?= $type_filter === 'all' ? 'selected' : '' ?>>All Types</option>
                                <option value="document" <?= $type_filter === 'document' ? 'selected' : '' ?>>Document</option>
                                <option value="link" <?= $type_filter === 'link' ? 'selected' : '' ?>>Link</option>
                                <option value="contact" <?= $type_filter === 'contact' ? 'selected' : '' ?>>Contact</option>
                                <option value="map" <?= $type_filter === 'map' ? 'selected' : '' ?>>Map</option>
                                <option value="notion-page" <?= $type_filter === 'notion-page' ? 'selected' : '' ?>>Notion Page</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="resources.php" class="btn btn-secondary">Clear</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Resources Grid -->
            <div class="row">
                <?php foreach ($resources as $resource): ?>
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><?= htmlspecialchars($resource['title']) ?></h6>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" 
                                       <?= $resource['is_active'] ? 'checked' : '' ?>
                                       onchange="toggleActive(<?= $resource['id'] ?>, this.checked)">
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="card-text"><?= htmlspecialchars($resource['description']) ?></p>
                            
                            <div class="mb-2">
                                <span class="badge bg-primary"><?= ucfirst($resource['category']) ?></span>
                                <span class="badge bg-secondary"><?= ucfirst(str_replace('-', ' ', $resource['resource_type'])) ?></span>
                            </div>
                            
                            <?php if ($resource['url']): ?>
                                <div class="mb-2">
                                    <a href="<?= htmlspecialchars($resource['url']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-link-45deg"></i> Visit Link
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($resource['file_url']): ?>
                                <div class="mb-2">
                                    <a href="<?= htmlspecialchars($resource['file_url']) ?>" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-file-earmark"></i> Download
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="mt-auto">
                                <small class="text-muted">
                                    Created by <?= htmlspecialchars($resource['first_name'] . ' ' . $resource['last_name']) ?>
                                    on <?= date('M j, Y', strtotime($resource['created_at'])) ?>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <?php if (empty($resources)): ?>
            <div class="text-center py-5">
                <i class="bi bi-journal-text display-1 text-muted"></i>
                <h3 class="mt-3">No resources found</h3>
                <p class="text-muted">No resources match your current filters.</p>
            </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<!-- Add Resource Modal -->
<div class="modal fade" id="addResourceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Resource</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add_resource">
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Title *</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
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
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="resource_type" class="form-label">Resource Type</label>
                                <select class="form-select" id="resource_type" name="resource_type" required>
                                    <option value="document">Document</option>
                                    <option value="link">Link</option>
                                    <option value="contact">Contact</option>
                                    <option value="map">Map</option>
                                    <option value="notion-page">Notion Page</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="url" class="form-label">URL (optional)</label>
                        <input type="url" class="form-control" id="url" name="url" placeholder="https://...">
                    </div>
                    
                    <div class="mb-3">
                        <label for="file_url" class="form-label">File URL (optional)</label>
                        <input type="text" class="form-control" id="file_url" name="file_url" placeholder="/uploads/file.pdf">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Resource</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleActive(resourceId, isActive) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.innerHTML = `
        <input type="hidden" name="action" value="toggle_active">
        <input type="hidden" name="resource_id" value="${resourceId}">
        <input type="hidden" name="new_status" value="${isActive ? 1 : 0}">
    `;
    document.body.appendChild(form);
    form.submit();
}
</script>

<?php include '../includes/templates/footer.php'; ?> 