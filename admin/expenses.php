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
        switch ($_POST['action']) {
            case 'approve':
                $expenseId = (int)$_POST['expense_id'];
                $db = getDB();
                $stmt = $db->prepare("UPDATE expenses SET status = 'approved', approved_by = ?, approved_at = CURRENT_TIMESTAMP WHERE id = ?");
                $stmt->bindValue(1, $user['id'], SQLITE3_INTEGER);
                $stmt->bindValue(2, $expenseId, SQLITE3_INTEGER);
                $stmt->execute();
                break;
                
            case 'reject':
                $expenseId = (int)$_POST['expense_id'];
                $db = getDB();
                $stmt = $db->prepare("UPDATE expenses SET status = 'rejected', approved_by = ?, approved_at = CURRENT_TIMESTAMP WHERE id = ?");
                $stmt->bindValue(1, $user['id'], SQLITE3_INTEGER);
                $stmt->bindValue(2, $expenseId, SQLITE3_INTEGER);
                $stmt->execute();
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

$page_title = "Expenses Management";
include '../includes/templates/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../includes/templates/sidebar-admin.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Expenses Management</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="exportExpenses()">Export</button>
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
                <div class="col-md-6">
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
                            <a href="expenses.php" class="btn btn-secondary">Clear</a>
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
                            <th>Actions</th>
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
                            <td>
                                <?php if ($expense['status'] === 'pending'): ?>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="action" value="approve">
                                        <input type="hidden" name="expense_id" value="<?= $expense['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Approve this expense?')">
                                            <i class="bi bi-check"></i> Approve
                                        </button>
                                    </form>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="action" value="reject">
                                        <input type="hidden" name="expense_id" value="<?= $expense['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Reject this expense?')">
                                            <i class="bi bi-x"></i> Reject
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <small class="text-muted">
                                        <?php if ($expense['approved_at']): ?>
                                            <?= date('M j, Y', strtotime($expense['approved_at'])) ?>
                                        <?php endif; ?>
                                    </small>
                                <?php endif; ?>
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

<script>
function exportExpenses() {
    // Simple export functionality
    const table = document.querySelector('table');
    const rows = Array.from(table.querySelectorAll('tr'));
    
    let csv = 'Date,Description,Amount,Category,Submitted By,Project,Status\n';
    
    rows.slice(1).forEach(row => {
        const cells = Array.from(row.querySelectorAll('td'));
        const rowData = cells.map(cell => {
            let text = cell.textContent.trim();
            // Remove action buttons text
            if (cell.querySelector('button')) {
                text = cell.querySelector('small') ? cell.querySelector('small').textContent.trim() : '';
            }
            return `"${text}"`;
        });
        csv += rowData.join(',') + '\n';
    });
    
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'expenses.csv';
    a.click();
    window.URL.revokeObjectURL(url);
}
</script>

<?php include '../includes/templates/footer.php'; ?> 