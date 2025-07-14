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

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $db = getDB();
        
        switch ($_POST['action']) {
            case 'add_expense':
                $description = trim($_POST['description']);
                $amount = (float)$_POST['amount'];
                $category = $_POST['category'];
                $date = $_POST['date'];
                $vendor = trim($_POST['vendor'] ?? '');
                $projectId = !empty($_POST['project_id']) ? (int)$_POST['project_id'] : null;
                $notes = trim($_POST['notes'] ?? '');
                
                if (!empty($description) && $amount > 0) {
                    $stmt = $db->prepare("
                        INSERT INTO expenses (description, amount, category, date, vendor, project_id, submitted_by, notes)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->bindValue(1, $description);
                    $stmt->bindValue(2, $amount);
                    $stmt->bindValue(3, $category);
                    $stmt->bindValue(4, $date);
                    $stmt->bindValue(5, $vendor);
                    $stmt->bindValue(6, $projectId);
                    $stmt->bindValue(7, $user['id'], SQLITE3_INTEGER);
                    $stmt->bindValue(8, $notes);
                    $stmt->execute();
                }
                break;
        }
    }
}

// Get expenses with filters
$status_filter = $_GET['status'] ?? 'all';
$category_filter = $_GET['category'] ?? 'all';

$db = getDB();
$where_conditions = [];
$params = [];

if ($status_filter !== 'all') {
    $where_conditions[] = "e.status = ?";
    $params[] = $status_filter;
}

if ($category_filter !== 'all') {
    $where_conditions[] = "e.category = ?";
    $params[] = $category_filter;
}

$where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

$query = "
    SELECT e.*, u.first_name, u.last_name, p.title as project_title
    FROM expenses e
    LEFT JOIN users u ON e.submitted_by = u.id
    LEFT JOIN projects p ON e.project_id = p.id
    $where_clause
    ORDER BY e.created_at DESC
";

$stmt = $db->prepare($query);
foreach ($params as $i => $param) {
    $stmt->bindValue($i + 1, $param);
}

$result = $stmt->execute();
$expenses = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $expenses[] = $row;
}

// Get summary stats
$stats_query = "
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
        SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
        SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected,
        SUM(CASE WHEN status = 'approved' THEN amount ELSE 0 END) as total_approved_amount
    FROM expenses
";
$stats_result = $db->query($stats_query);
$stats = $stats_result->fetchArray(SQLITE3_ASSOC);

// Get projects for dropdown
$projects_query = "SELECT id, title FROM projects WHERE status IN ('open', 'in-progress') ORDER BY title";
$projects_result = $db->query($projects_query);
$projects = [];
while ($row = $projects_result->fetchArray(SQLITE3_ASSOC)) {
    $projects[] = $row;
}

$page_title = "Costs & Resource Flow";
include '../includes/templates/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../includes/templates/sidebar-stewart.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Costs & Resource Flow</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
                            <i class="bi bi-plus"></i> Add Expense
                        </button>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <h5 class="card-title">Total Expenses</h5>
                            <h3><?= $stats['total'] ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-warning">
                        <div class="card-body">
                            <h5 class="card-title">Pending</h5>
                            <h3><?= $stats['pending'] ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <h5 class="card-title">Approved</h5>
                            <h3><?= $stats['approved'] ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-info">
                        <div class="card-body">
                            <h5 class="card-title">Total Approved</h5>
                            <h3>€<?= number_format($stats['total_approved_amount'], 2) ?></h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="row mb-3">
                <div class="col-md-8">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <select name="status" class="form-select">
                                <option value="all" <?= $status_filter === 'all' ? 'selected' : '' ?>>All Status</option>
                                <option value="pending" <?= $status_filter === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="approved" <?= $status_filter === 'approved' ? 'selected' : '' ?>>Approved</option>
                                <option value="rejected" <?= $status_filter === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="category" class="form-select">
                                <option value="all" <?= $category_filter === 'all' ? 'selected' : '' ?>>All Categories</option>
                                <option value="tools" <?= $category_filter === 'tools' ? 'selected' : '' ?>>Tools</option>
                                <option value="materials" <?= $category_filter === 'materials' ? 'selected' : '' ?>>Materials</option>
                                <option value="utilities" <?= $category_filter === 'utilities' ? 'selected' : '' ?>>Utilities</option>
                                <option value="maintenance" <?= $category_filter === 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
                                <option value="events" <?= $category_filter === 'events' ? 'selected' : '' ?>>Events</option>
                                <option value="admin" <?= $category_filter === 'admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="other" <?= $category_filter === 'other' ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="costs.php" class="btn btn-secondary">Clear</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Expenses Table -->
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Category</th>
                            <th>Submitted By</th>
                            <th>Project</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($expenses as $expense): ?>
                        <tr>
                            <td><?= date('M j, Y', strtotime($expense['date'])) ?></td>
                            <td>
                                <strong><?= htmlspecialchars($expense['description']) ?></strong>
                                <?php if ($expense['notes']): ?>
                                    <br><small class="text-muted"><?= htmlspecialchars($expense['notes']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td>€<?= number_format($expense['amount'], 2) ?></td>
                            <td>
                                <span class="badge bg-secondary"><?= ucfirst($expense['category']) ?></span>
                            </td>
                            <td><?= htmlspecialchars($expense['first_name'] . ' ' . $expense['last_name']) ?></td>
                            <td>
                                <?php if ($expense['project_title']): ?>
                                    <?= htmlspecialchars($expense['project_title']) ?>
                                <?php else: ?>
                                    <span class="text-muted">No project</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                $status_class = [
                                    'pending' => 'warning',
                                    'approved' => 'success',
                                    'rejected' => 'danger'
                                ];
                                ?>
                                <span class="badge bg-<?= $status_class[$expense['status']] ?>">
                                    <?= ucfirst($expense['status']) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if (empty($expenses)): ?>
            <div class="text-center py-5">
                <i class="bi bi-receipt display-1 text-muted"></i>
                <h3 class="mt-3">No expenses found</h3>
                <p class="text-muted">No expenses match your current filters.</p>
            </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<!-- Add Expense Modal -->
<div class="modal fade" id="addExpenseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Expense</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add_expense">
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description *</label>
                        <input type="text" class="form-control" id="description" name="description" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="amount" class="form-label">Amount (€) *</label>
                                <input type="number" class="form-control" id="amount" name="amount" step="0.01" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category" class="form-label">Category *</label>
                                <select class="form-select" id="category" name="category" required>
                                    <option value="tools">Tools</option>
                                    <option value="materials">Materials</option>
                                    <option value="utilities">Utilities</option>
                                    <option value="maintenance">Maintenance</option>
                                    <option value="events">Events</option>
                                    <option value="admin">Admin</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="date" class="form-label">Date *</label>
                                <input type="date" class="form-control" id="date" name="date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="vendor" class="form-label">Vendor</label>
                                <input type="text" class="form-control" id="vendor" name="vendor">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="project_id" class="form-label">Project (optional)</label>
                        <select class="form-select" id="project_id" name="project_id">
                            <option value="">No project</option>
                            <?php foreach ($projects as $project): ?>
                                <option value="<?= $project['id'] ?>"><?= htmlspecialchars($project['title']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Expense</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Set default date to today
document.getElementById('date').value = new Date().toISOString().split('T')[0];
</script>

<?php include '../includes/templates/footer.php'; ?> 