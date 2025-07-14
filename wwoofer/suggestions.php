<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/db.php';

// Check if user is logged in and is wwoofer
if (!isLoggedIn() || $_SESSION['user_role'] !== 'wwoofer') {
    header('Location: ../login.php');
    exit;
}

$user = $_SESSION['user'];

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $db = getDB();
        
        switch ($_POST['action']) {
            case 'add_suggestion':
                $title = trim($_POST['title']);
                $description = trim($_POST['description']);
                
                if (!empty($title)) {
                    $stmt = $db->prepare("
                        INSERT INTO suggestions (title, description, submitted_by)
                        VALUES (?, ?, ?)
                    ");
                    $stmt->bindValue(1, $title);
                    $stmt->bindValue(2, $description);
                    $stmt->bindValue(3, $user['id'], SQLITE3_INTEGER);
                    $stmt->execute();
                }
                break;
        }
    }
}

// Get user's suggestions with filters
$status_filter = $_GET['status'] ?? 'all';

$db = getDB();
$where_clause = $status_filter !== 'all' ? "AND s.status = ?" : "";

$query = "
    SELECT s.*, r.first_name as reviewer_first_name, r.last_name as reviewer_last_name
    FROM suggestions s
    LEFT JOIN users r ON s.reviewed_by = r.id
    WHERE s.submitted_by = ?
    $where_clause
    ORDER BY s.created_at DESC
";

$stmt = $db->prepare($query);
$stmt->bindValue(1, $user['id'], SQLITE3_INTEGER);
if ($status_filter !== 'all') {
    $stmt->bindValue(2, $status_filter);
}

$result = $stmt->execute();
$suggestions = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $suggestions[] = $row;
}

// Get summary stats for user's suggestions
$stats_query = "
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status = 'new' THEN 1 ELSE 0 END) as new_count,
        SUM(CASE WHEN status = 'reviewing' THEN 1 ELSE 0 END) as reviewing_count,
        SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_count,
        SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected_count
    FROM suggestions
    WHERE submitted_by = ?
";
$stmt = $db->prepare($stats_query);
$stmt->bindValue(1, $user['id'], SQLITE3_INTEGER);
$result = $stmt->execute();
$stats = $result->fetchArray(SQLITE3_ASSOC);

$page_title = "My Suggestions";
include '../includes/templates/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../includes/templates/sidebar-wwoofer.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">My Suggestions</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addSuggestionModal">
                            <i class="bi bi-plus"></i> Add Suggestion
                        </button>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-2">
                    <div class="card text-white bg-primary">
                        <div class="card-body text-center">
                            <h5 class="card-title">Total</h5>
                            <h3><?= $stats['total'] ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-white bg-secondary">
                        <div class="card-body text-center">
                            <h5 class="card-title">New</h5>
                            <h3><?= $stats['new_count'] ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-white bg-warning">
                        <div class="card-body text-center">
                            <h5 class="card-title">Reviewing</h5>
                            <h3><?= $stats['reviewing_count'] ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-white bg-success">
                        <div class="card-body text-center">
                            <h5 class="card-title">Approved</h5>
                            <h3><?= $stats['approved_count'] ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-white bg-danger">
                        <div class="card-body text-center">
                            <h5 class="card-title">Rejected</h5>
                            <h3><?= $stats['rejected_count'] ?></h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <form method="GET" class="row g-3">
                        <div class="col-md-6">
                            <select name="status" class="form-select">
                                <option value="all" <?= $status_filter === 'all' ? 'selected' : '' ?>>All Status</option>
                                <option value="new" <?= $status_filter === 'new' ? 'selected' : '' ?>>New</option>
                                <option value="reviewing" <?= $status_filter === 'reviewing' ? 'selected' : '' ?>>Reviewing</option>
                                <option value="approved" <?= $status_filter === 'approved' ? 'selected' : '' ?>>Approved</option>
                                <option value="rejected" <?= $status_filter === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="suggestions.php" class="btn btn-secondary">Clear</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Suggestions List -->
            <div class="row">
                <?php foreach ($suggestions as $suggestion): ?>
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><?= htmlspecialchars($suggestion['title']) ?></h6>
                            <?php
                            $status_class = [
                                'new' => 'secondary',
                                'reviewing' => 'warning',
                                'approved' => 'success',
                                'rejected' => 'danger'
                            ];
                            ?>
                            <span class="badge bg-<?= $status_class[$suggestion['status']] ?>">
                                <?= ucfirst($suggestion['status']) ?>
                            </span>
                        </div>
                        <div class="card-body">
                            <p class="card-text"><?= htmlspecialchars($suggestion['description']) ?></p>
                            
                            <div class="row mb-2">
                                <div class="col-6">
                                    <small class="text-muted">
                                        <strong>Submitted:</strong><br>
                                        <?= date('M j, Y', strtotime($suggestion['created_at'])) ?>
                                    </small>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">
                                        <strong>Status:</strong><br>
                                        <?= ucfirst($suggestion['status']) ?>
                                    </small>
                                </div>
                            </div>

                            <?php if ($suggestion['review_notes']): ?>
                                <div class="alert alert-info py-2">
                                    <small><strong>Review Notes:</strong> <?= htmlspecialchars($suggestion['review_notes']) ?></small>
                                </div>
                            <?php endif; ?>

                            <?php if ($suggestion['reviewed_by']): ?>
                                <div class="mt-2">
                                    <small class="text-muted">
                                        Reviewed by: <?= htmlspecialchars($suggestion['reviewer_first_name'] . ' ' . $suggestion['reviewer_last_name']) ?>
                                        on <?= date('M j, Y', strtotime($suggestion['reviewed_at'])) ?>
                                    </small>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <?php if (empty($suggestions)): ?>
            <div class="text-center py-5">
                <i class="bi bi-lightbulb display-1 text-muted"></i>
                <h3 class="mt-3">No suggestions found</h3>
                <p class="text-muted">No suggestions match your current filters.</p>
            </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<!-- Add Suggestion Modal -->
<div class="modal fade" id="addSuggestionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Suggestion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add_suggestion">
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Title *</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4" placeholder="Describe your idea or suggestion..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Suggestion</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/templates/footer.php'; ?> 