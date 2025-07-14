<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/db.php';

// Check if user is logged in and is stewart
if (!isLoggedIn() || $_SESSION['user_role'] !== 'stewart') {
    header('Location: ../login.php');
    exit;
}

$user = $_SESSION['user'];

// Get resources with filters
$category_filter = $_GET['category'] ?? 'all';

$db = getDB();
$where_clause = $category_filter !== 'all' ? "WHERE category = ?" : "";

$query = "
    SELECT lr.*, u.first_name, u.last_name
    FROM learning_resources lr
    LEFT JOIN users u ON lr.created_by = u.id
    WHERE lr.is_active = 1
    $where_clause
    ORDER BY lr.created_at DESC
";

$stmt = $db->prepare($query);
if ($category_filter !== 'all') {
    $stmt->bindValue(1, $category_filter);
}

$result = $stmt->execute();
$resources = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $resources[] = $row;
}

// Get summary stats
$stats_query = "
    SELECT 
        COUNT(*) as total_resources,
        COUNT(DISTINCT category) as categories,
        SUM(CASE WHEN resource_type = 'document' THEN 1 ELSE 0 END) as documents,
        SUM(CASE WHEN resource_type = 'link' THEN 1 ELSE 0 END) as links
    FROM learning_resources 
    WHERE is_active = 1
";
$stats_result = $db->query($stats_query);
$stats = $stats_result->fetchArray(SQLITE3_ASSOC);

$page_title = "Resources";
include '../includes/templates/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../includes/templates/sidebar-stewart.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Resources</h1>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-white bg-primary">
                        <div class="card-body text-center">
                            <h5 class="card-title">Total Resources</h5>
                            <h3><?= $stats['total_resources'] ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success">
                        <div class="card-body text-center">
                            <h5 class="card-title">Documents</h5>
                            <h3><?= $stats['documents'] ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-info">
                        <div class="card-body text-center">
                            <h5 class="card-title">Links</h5>
                            <h3><?= $stats['links'] ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-warning">
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
                        <div class="card-header">
                            <h6 class="mb-0"><?= htmlspecialchars($resource['title']) ?></h6>
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

<?php include '../includes/templates/footer.php'; ?> 