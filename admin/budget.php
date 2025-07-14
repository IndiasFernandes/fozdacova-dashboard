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

// Get budget data
$db = getDB();

// Get expenses by category for current year
$current_year = date('Y');
$expenses_query = "
    SELECT 
        category,
        SUM(amount) as total_amount,
        COUNT(*) as count
    FROM expenses 
    WHERE status = 'approved' 
    AND strftime('%Y', date) = ?
    GROUP BY category
    ORDER BY total_amount DESC
";
$stmt = $db->prepare($expenses_query);
$stmt->bindValue(1, $current_year);
$result = $stmt->execute();
$expenses_by_category = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $expenses_by_category[] = $row;
}

// Get monthly expenses for current year
$monthly_query = "
    SELECT 
        strftime('%m', date) as month,
        SUM(amount) as total_amount
    FROM expenses 
    WHERE status = 'approved' 
    AND strftime('%Y', date) = ?
    GROUP BY strftime('%m', date)
    ORDER BY month
";
$stmt = $db->prepare($monthly_query);
$stmt->bindValue(1, $current_year);
$result = $stmt->execute();
$monthly_expenses = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $monthly_expenses[] = $row;
}

// Get total approved expenses for current year
$total_query = "
    SELECT SUM(amount) as total_approved
    FROM expenses 
    WHERE status = 'approved' 
    AND strftime('%Y', date) = ?
";
$stmt = $db->prepare($total_query);
$stmt->bindValue(1, $current_year);
$result = $stmt->execute();
$total_data = $result->fetchArray(SQLITE3_ASSOC);
$total_approved = $total_data['total_approved'] ?? 0;

// Get pending expenses
$pending_query = "
    SELECT SUM(amount) as total_pending
    FROM expenses 
    WHERE status = 'pending'
";
$result = $db->query($pending_query);
$pending_data = $result->fetchArray(SQLITE3_ASSOC);
$total_pending = $pending_data['total_pending'] ?? 0;

// Get recent large expenses
$recent_large_query = "
    SELECT e.*, u.first_name, u.last_name
    FROM expenses e
    LEFT JOIN users u ON e.submitted_by = u.id
    WHERE e.status = 'approved'
    AND e.amount > 50
    ORDER BY e.amount DESC
    LIMIT 10
";
$result = $db->query($recent_large_query);
$recent_large = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $recent_large[] = $row;
}

$page_title = "Budget Overview";
include '../includes/templates/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../includes/templates/sidebar-admin.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Budget Overview</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="exportBudget()">Export Report</button>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <h5 class="card-title">Total Approved (<?= $current_year ?>)</h5>
                            <h3>€<?= number_format($total_approved, 2) ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-warning">
                        <div class="card-body">
                            <h5 class="card-title">Pending Expenses</h5>
                            <h3>€<?= number_format($total_pending, 2) ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-info">
                        <div class="card-body">
                            <h5 class="card-title">Average per Month</h5>
                            <h3>€<?= number_format($total_approved / 12, 2) ?></h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Expenses by Category -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Expenses by Category (<?= $current_year ?>)</h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($expenses_by_category)): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Category</th>
                                                <th>Amount</th>
                                                <th>Count</th>
                                                <th>%</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($expenses_by_category as $category): ?>
                                                <tr>
                                                    <td><?= ucfirst($category['category']) ?></td>
                                                    <td>€<?= number_format($category['total_amount'], 2) ?></td>
                                                    <td><?= $category['count'] ?></td>
                                                    <td><?= number_format(($category['total_amount'] / $total_approved) * 100, 1) ?>%</td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="text-muted text-center">No expenses data available</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Recent Large Expenses -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Recent Large Expenses (>€50)</h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($recent_large)): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Description</th>
                                                <th>Amount</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($recent_large as $expense): ?>
                                                <tr>
                                                    <td>
                                                        <?= htmlspecialchars($expense['description']) ?>
                                                        <br><small class="text-muted">by <?= htmlspecialchars($expense['first_name'] . ' ' . $expense['last_name']) ?></small>
                                                    </td>
                                                    <td>€<?= number_format($expense['amount'], 2) ?></td>
                                                    <td><?= date('M j', strtotime($expense['date'])) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="text-muted text-center">No large expenses found</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Chart -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Monthly Expenses (<?= $current_year ?>)</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="monthlyChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Budget Insights -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Budget Insights</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Top Spending Categories</h6>
                                    <?php if (!empty($expenses_by_category)): ?>
                                        <ol class="list-unstyled">
                                            <?php foreach (array_slice($expenses_by_category, 0, 3) as $category): ?>
                                                <li class="mb-2">
                                                    <strong><?= ucfirst($category['category']) ?></strong>
                                                    <span class="text-muted"> - €<?= number_format($category['total_amount'], 2) ?></span>
                                                </li>
                                            <?php endforeach; ?>
                                        </ol>
                                    <?php else: ?>
                                        <p class="text-muted">No data available</p>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <h6>Recommendations</h6>
                                    <ul class="list-unstyled">
                                        <li class="mb-2">
                                            <i class="bi bi-lightbulb text-warning"></i>
                                            Review pending expenses regularly
                                        </li>
                                        <li class="mb-2">
                                            <i class="bi bi-lightbulb text-warning"></i>
                                            Set monthly budget limits by category
                                        </li>
                                        <li class="mb-2">
                                            <i class="bi bi-lightbulb text-warning"></i>
                                            Track seasonal spending patterns
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Monthly expenses chart
const ctx = document.getElementById('monthlyChart').getContext('2d');
const monthlyData = <?= json_encode($monthly_expenses) ?>;

const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
const amounts = new Array(12).fill(0);

monthlyData.forEach(item => {
    const monthIndex = parseInt(item.month) - 1;
    amounts[monthIndex] = parseFloat(item.total_amount);
});

new Chart(ctx, {
    type: 'line',
    data: {
        labels: months,
        datasets: [{
            label: 'Monthly Expenses (€)',
            data: amounts,
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '€' + value;
                    }
                }
            }
        }
    }
});

function exportBudget() {
    // Simple export functionality
    const data = {
        total_approved: <?= $total_approved ?>,
        total_pending: <?= $total_pending ?>,
        current_year: '<?= $current_year ?>',
        categories: <?= json_encode($expenses_by_category) ?>
    };
    
    const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'budget-report.json';
    a.click();
    window.URL.revokeObjectURL(url);
}
</script>

<?php include '../includes/templates/footer.php'; ?> 