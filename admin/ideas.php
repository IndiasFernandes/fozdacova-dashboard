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
            case 'update_status':
                $suggestionId = (int)$_POST['suggestion_id'];
                $newStatus = $_POST['new_status'];
                $reviewNotes = $_POST['review_notes'] ?? '';
                
                $stmt = $db->prepare("
                    UPDATE suggestions 
                    SET status = ?, reviewed_by = ?, reviewed_at = CURRENT_TIMESTAMP, review_notes = ?
                    WHERE id = ?
                ");
                $stmt->bindValue(1, $newStatus);
                $stmt->bindValue(2, $user['id'], SQLITE3_INTEGER);
                $stmt->bindValue(3, $reviewNotes);
                $stmt->bindValue(4, $suggestionId, SQLITE3_INTEGER);
                $stmt->execute();
                break;
        }
    }
}

// Get suggestions with filters
$status_filter = $_GET['status'] ?? 'all';

$db = getDB();
$where_clause = $status_filter !== 'all' ? "WHERE s.status = ?" : "";

$query = "
    SELECT s.*, u.first_name, u.last_name, u.role as user_role,
           r.first_name as reviewer_first_name, r.last_name as reviewer_last_name
    FROM suggestions s
    LEFT JOIN users u ON s.submitted_by = u.id
    LEFT JOIN users r ON s.reviewed_by = r.id
    $where_clause
    ORDER BY s.created_at DESC
";

$stmt = $db->prepare($query);
if ($status_filter !== 'all') {
    $stmt->bindValue(1, $status_filter);
}

$result = $stmt->execute();
$suggestions = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $suggestions[] = $row;
}

// Get summary stats
$stats_query = "
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status = 'new' THEN 1 ELSE 0 END) as new_count,
        SUM(CASE WHEN status = 'reviewing' THEN 1 ELSE 0 END) as reviewing_count,
        SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_count,
        SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected_count
    FROM suggestions
";
$stats_result = $db->query($stats_query);
$stats = $stats_result->fetchArray(SQLITE3_ASSOC);

$page_title = "Ideas & Suggestions";
include '../includes/templates/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../includes/templates/sidebar-admin.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Ideas & Suggestions</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="exportSuggestions()">Export</button>
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
                            <a href="ideas.php" class="btn btn-secondary">Clear</a>
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
                                        <strong>Submitted by:</strong><br>
                                        <?= htmlspecialchars($suggestion['first_name'] . ' ' . $suggestion['last_name']) ?>
                                        <span class="badge bg-light text-dark"><?= ucfirst($suggestion['user_role']) ?></span>
                                    </small>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">
                                        <strong>Date:</strong><br>
                                        <?= date('M j, Y', strtotime($suggestion['created_at'])) ?>
                                    </small>
                                </div>
                            </div>

                            <?php if ($suggestion['review_notes']): ?>
                                <div class="alert alert-info py-2">
                                    <small><strong>Review Notes:</strong> <?= htmlspecialchars($suggestion['review_notes']) ?></small>
                                </div>
                            <?php endif; ?>

                            <?php if ($suggestion['status'] === 'new'): ?>
                                <div class="mt-3">
                                    <button class="btn btn-sm btn-warning me-2" onclick="updateStatus(<?= $suggestion['id'] ?>, 'reviewing')">
                                        <i class="bi bi-eye"></i> Start Review
                                    </button>
                                    <button class="btn btn-sm btn-success me-2" onclick="updateStatus(<?= $suggestion['id'] ?>, 'approved')">
                                        <i class="bi bi-check"></i> Approve
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="updateStatus(<?= $suggestion['id'] ?>, 'rejected')">
                                        <i class="bi bi-x"></i> Reject
                                    </button>
                                </div>
                            <?php elseif ($suggestion['status'] === 'reviewing'): ?>
                                <div class="mt-3">
                                    <button class="btn btn-sm btn-success me-2" onclick="updateStatus(<?= $suggestion['id'] ?>, 'approved')">
                                        <i class="bi bi-check"></i> Approve
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="updateStatus(<?= $suggestion['id'] ?>, 'rejected')">
                                        <i class="bi bi-x"></i> Reject
                                    </button>
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

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Suggestion Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="update_status">
                    <input type="hidden" name="suggestion_id" id="suggestionId">
                    <input type="hidden" name="new_status" id="newStatus">
                    
                    <div class="mb-3">
                        <label for="reviewNotes" class="form-label">Review Notes (optional)</label>
                        <textarea class="form-control" id="reviewNotes" name="review_notes" rows="3" placeholder="Add any notes about this decision..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function updateStatus(suggestionId, newStatus) {
    document.getElementById('suggestionId').value = suggestionId;
    document.getElementById('newStatus').value = newStatus;
    
    const modal = new bootstrap.Modal(document.getElementById('statusModal'));
    modal.show();
}

function exportSuggestions() {
    // Simple export functionality
    const suggestions = <?= json_encode($suggestions) ?>;
    
    let csv = 'Title,Description,Status,Submitted By,Date,Review Notes\n';
    
    suggestions.forEach(suggestion => {
        const row = [
            suggestion.title,
            suggestion.description,
            suggestion.status,
            suggestion.first_name + ' ' + suggestion.last_name,
            new Date(suggestion.created_at).toLocaleDateString(),
            suggestion.review_notes || ''
        ];
        csv += row.map(field => `"${field}"`).join(',') + '\n';
    });
    
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'suggestions.csv';
    a.click();
    window.URL.revokeObjectURL(url);
}
</script>

<?php include '../includes/templates/footer.php'; ?> 