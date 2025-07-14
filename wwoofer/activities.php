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
            case 'add_activity':
                $title = trim($_POST['title']);
                $description = trim($_POST['description']);
                $hoursWorked = (float)$_POST['hours_worked'];
                $mood = $_POST['mood'];
                $category = $_POST['category'];
                $projectId = !empty($_POST['project_id']) ? (int)$_POST['project_id'] : null;
                $activityDate = $_POST['activity_date'];
                
                if (!empty($title) && $hoursWorked > 0) {
                    $stmt = $db->prepare("
                        INSERT INTO activities (title, description, hours_worked, mood, category, project_id, user_id, activity_date)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->bindValue(1, $title);
                    $stmt->bindValue(2, $description);
                    $stmt->bindValue(3, $hoursWorked);
                    $stmt->bindValue(4, $mood);
                    $stmt->bindValue(5, $category);
                    $stmt->bindValue(6, $projectId);
                    $stmt->bindValue(7, $user['id'], SQLITE3_INTEGER);
                    $stmt->bindValue(8, $activityDate);
                    $stmt->execute();
                }
                break;
        }
    }
}

// Get activities with filters
$category_filter = $_GET['category'] ?? 'all';
$mood_filter = $_GET['mood'] ?? 'all';

$db = getDB();
$where_conditions = ["a.user_id = ?"];
$params = [$user['id']];

if ($category_filter !== 'all') {
    $where_conditions[] = "a.category = ?";
    $params[] = $category_filter;
}

if ($mood_filter !== 'all') {
    $where_conditions[] = "a.mood = ?";
    $params[] = $mood_filter;
}

$where_clause = "WHERE " . implode(" AND ", $where_conditions);

$query = "
    SELECT a.*, p.title as project_title
    FROM activities a
    LEFT JOIN projects p ON a.project_id = p.id
    $where_clause
    ORDER BY a.activity_date DESC, a.created_at DESC
";

$stmt = $db->prepare($query);
foreach ($params as $i => $param) {
    $stmt->bindValue($i + 1, $param);
}

$result = $stmt->execute();
$activities = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $activities[] = $row;
}

// Get summary stats
$stats_query = "
    SELECT 
        COUNT(*) as total_activities,
        SUM(hours_worked) as total_hours,
        AVG(hours_worked) as avg_hours,
        COUNT(DISTINCT activity_date) as days_worked
    FROM activities 
    WHERE user_id = ?
";
$stmt = $db->prepare($stats_query);
$stmt->bindValue(1, $user['id'], SQLITE3_INTEGER);
$result = $stmt->execute();
$stats = $result->fetchArray(SQLITE3_ASSOC);

// Get projects for dropdown
$projects_query = "SELECT id, title FROM projects WHERE status IN ('open', 'in-progress') ORDER BY title";
$projects_result = $db->query($projects_query);
$projects = [];
while ($row = $projects_result->fetchArray(SQLITE3_ASSOC)) {
    $projects[] = $row;
}

$page_title = "My Activities";
include '../includes/templates/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../includes/templates/sidebar-wwoofer.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">My Activities</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addActivityModal">
                            <i class="bi bi-plus"></i> Log Activity
                        </button>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-white bg-primary">
                        <div class="card-body text-center">
                            <h5 class="card-title">Total Activities</h5>
                            <h3><?= $stats['total_activities'] ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success">
                        <div class="card-body text-center">
                            <h5 class="card-title">Total Hours</h5>
                            <h3><?= number_format($stats['total_hours'], 1) ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-info">
                        <div class="card-body text-center">
                            <h5 class="card-title">Avg Hours/Day</h5>
                            <h3><?= number_format($stats['avg_hours'], 1) ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-warning">
                        <div class="card-body text-center">
                            <h5 class="card-title">Days Worked</h5>
                            <h3><?= $stats['days_worked'] ?></h3>
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
                                <option value="water-systems" <?= $category_filter === 'water-systems' ? 'selected' : '' ?>>Water Systems</option>
                                <option value="natural-building" <?= $category_filter === 'natural-building' ? 'selected' : '' ?>>Natural Building</option>
                                <option value="gardening" <?= $category_filter === 'gardening' ? 'selected' : '' ?>>Gardening</option>
                                <option value="land-maintenance" <?= $category_filter === 'land-maintenance' ? 'selected' : '' ?>>Land Maintenance</option>
                                <option value="housekeeping" <?= $category_filter === 'housekeeping' ? 'selected' : '' ?>>Housekeeping</option>
                                <option value="community" <?= $category_filter === 'community' ? 'selected' : '' ?>>Community</option>
                                <option value="education" <?= $category_filter === 'education' ? 'selected' : '' ?>>Education</option>
                                <option value="sustainability" <?= $category_filter === 'sustainability' ? 'selected' : '' ?>>Sustainability</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="mood" class="form-select">
                                <option value="all" <?= $mood_filter === 'all' ? 'selected' : '' ?>>All Moods</option>
                                <option value="great" <?= $mood_filter === 'great' ? 'selected' : '' ?>>Great</option>
                                <option value="good" <?= $mood_filter === 'good' ? 'selected' : '' ?>>Good</option>
                                <option value="okay" <?= $mood_filter === 'okay' ? 'selected' : '' ?>>Okay</option>
                                <option value="difficult" <?= $mood_filter === 'difficult' ? 'selected' : '' ?>>Difficult</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="activities.php" class="btn btn-secondary">Clear</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Activities List -->
            <div class="row">
                <?php foreach ($activities as $activity): ?>
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><?= htmlspecialchars($activity['title']) ?></h6>
                            <?php
                            $mood_class = [
                                'great' => 'success',
                                'good' => 'primary',
                                'okay' => 'warning',
                                'difficult' => 'danger'
                            ];
                            ?>
                            <span class="badge bg-<?= $mood_class[$activity['mood']] ?>">
                                <?= ucfirst($activity['mood']) ?>
                            </span>
                        </div>
                        <div class="card-body">
                            <p class="card-text"><?= htmlspecialchars($activity['description']) ?></p>
                            
                            <div class="row mb-2">
                                <div class="col-6">
                                    <small class="text-muted">
                                        <strong>Hours:</strong><br>
                                        <?= $activity['hours_worked'] ?> hours
                                    </small>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">
                                        <strong>Date:</strong><br>
                                        <?= date('M j, Y', strtotime($activity['activity_date'])) ?>
                                    </small>
                                </div>
                            </div>
                            
                            <div class="mb-2">
                                <span class="badge bg-secondary"><?= ucfirst(str_replace('-', ' ', $activity['category'])) ?></span>
                                <?php if ($activity['project_title']): ?>
                                    <span class="badge bg-info"><?= htmlspecialchars($activity['project_title']) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <?php if (empty($activities)): ?>
            <div class="text-center py-5">
                <i class="bi bi-activity display-1 text-muted"></i>
                <h3 class="mt-3">No activities found</h3>
                <p class="text-muted">No activities match your current filters.</p>
            </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<!-- Add Activity Modal -->
<div class="modal fade" id="addActivityModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Log New Activity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add_activity">
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Activity Title *</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Describe what you did..."></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="hours_worked" class="form-label">Hours Worked *</label>
                                <input type="number" class="form-control" id="hours_worked" name="hours_worked" step="0.5" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="mood" class="form-label">How was it? *</label>
                                <select class="form-select" id="mood" name="mood" required>
                                    <option value="great">Great!</option>
                                    <option value="good">Good</option>
                                    <option value="okay">Okay</option>
                                    <option value="difficult">Difficult</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category" class="form-label">Category *</label>
                                <select class="form-select" id="category" name="category" required>
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
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="activity_date" class="form-label">Date *</label>
                                <input type="date" class="form-control" id="activity_date" name="activity_date" required>
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Log Activity</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Set default date to today
document.getElementById('activity_date').value = new Date().toISOString().split('T')[0];
</script>

<?php include '../includes/templates/footer.php'; ?> 