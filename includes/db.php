<?php
require_once 'config.php';

/**
 * Projects CRUD Operations
 */
function createProject($title, $description, $category, $status, $priority, $maxParticipants, $location, $startDate, $endDate, $createdBy) {
    $db = getDB();
    
    $stmt = $db->prepare('INSERT INTO projects (title, description, category, status, priority, max_participants, location, start_date, end_date, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->bindValue(1, $title, SQLITE3_TEXT);
    $stmt->bindValue(2, $description, SQLITE3_TEXT);
    $stmt->bindValue(3, $category, SQLITE3_TEXT);
    $stmt->bindValue(4, $status, SQLITE3_TEXT);
    $stmt->bindValue(5, $priority, SQLITE3_TEXT);
    $stmt->bindValue(6, $maxParticipants, SQLITE3_INTEGER);
    $stmt->bindValue(7, $location, SQLITE3_TEXT);
    $stmt->bindValue(8, $startDate, SQLITE3_TEXT);
    $stmt->bindValue(9, $endDate, SQLITE3_TEXT);
    $stmt->bindValue(10, $createdBy, SQLITE3_INTEGER);
    
    return $stmt->execute();
}

function getProjects($status = null, $category = null, $limit = 50) {
    $db = getDB();
    
    $sql = 'SELECT p.*, u.first_name, u.last_name FROM projects p LEFT JOIN users u ON p.created_by = u.id';
    $conditions = [];
    $params = [];
    
    if ($status) {
        $conditions[] = 'p.status = ?';
        $params[] = $status;
    }
    
    if ($category) {
        $conditions[] = 'p.category = ?';
        $params[] = $category;
    }
    
    if (!empty($conditions)) {
        $sql .= ' WHERE ' . implode(' AND ', $conditions);
    }
    
    $sql .= ' ORDER BY p.created_at DESC LIMIT ?';
    $params[] = $limit;
    
    $stmt = $db->prepare($sql);
    foreach ($params as $i => $param) {
        $stmt->bindValue($i + 1, $param);
    }
    
    $result = $stmt->execute();
    $projects = [];
    
    while ($project = $result->fetchArray(SQLITE3_ASSOC)) {
        $projects[] = $project;
    }
    
    return $projects;
}

function getProjectById($id) {
    $db = getDB();
    
    $stmt = $db->prepare('SELECT p.*, u.first_name, u.last_name FROM projects p LEFT JOIN users u ON p.created_by = u.id WHERE p.id = ?');
    $stmt->bindValue(1, $id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    
    return $result->fetchArray(SQLITE3_ASSOC);
}

function updateProject($id, $title, $description, $category, $status, $priority, $maxParticipants, $location, $startDate, $endDate) {
    $db = getDB();
    
    $stmt = $db->prepare('UPDATE projects SET title = ?, description = ?, category = ?, status = ?, priority = ?, max_participants = ?, location = ?, start_date = ?, end_date = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?');
    $stmt->bindValue(1, $title, SQLITE3_TEXT);
    $stmt->bindValue(2, $description, SQLITE3_TEXT);
    $stmt->bindValue(3, $category, SQLITE3_TEXT);
    $stmt->bindValue(4, $status, SQLITE3_TEXT);
    $stmt->bindValue(5, $priority, SQLITE3_TEXT);
    $stmt->bindValue(6, $maxParticipants, SQLITE3_INTEGER);
    $stmt->bindValue(7, $location, SQLITE3_TEXT);
    $stmt->bindValue(8, $startDate, SQLITE3_TEXT);
    $stmt->bindValue(9, $endDate, SQLITE3_TEXT);
    $stmt->bindValue(10, $id, SQLITE3_INTEGER);
    
    return $stmt->execute();
}

/**
 * Suggestions CRUD Operations
 */
function createSuggestion($title, $description, $submittedBy) {
    $db = getDB();
    
    $stmt = $db->prepare('INSERT INTO suggestions (title, description, submitted_by) VALUES (?, ?, ?)');
    $stmt->bindValue(1, $title, SQLITE3_TEXT);
    $stmt->bindValue(2, $description, SQLITE3_TEXT);
    $stmt->bindValue(3, $submittedBy, SQLITE3_INTEGER);
    
    return $stmt->execute();
}

function getSuggestions($status = null, $limit = 50) {
    $db = getDB();
    
    $sql = 'SELECT s.*, u1.first_name as submitter_first_name, u1.last_name as submitter_last_name, u2.first_name as reviewer_first_name, u2.last_name as reviewer_last_name FROM suggestions s LEFT JOIN users u1 ON s.submitted_by = u1.id LEFT JOIN users u2 ON s.reviewed_by = u2.id';
    
    if ($status) {
        $sql .= ' WHERE s.status = ?';
    }
    
    $sql .= ' ORDER BY s.created_at DESC LIMIT ?';
    
    $stmt = $db->prepare($sql);
    if ($status) {
        $stmt->bindValue(1, $status, SQLITE3_TEXT);
        $stmt->bindValue(2, $limit, SQLITE3_INTEGER);
    } else {
        $stmt->bindValue(1, $limit, SQLITE3_INTEGER);
    }
    
    $result = $stmt->execute();
    $suggestions = [];
    
    while ($suggestion = $result->fetchArray(SQLITE3_ASSOC)) {
        $suggestions[] = $suggestion;
    }
    
    return $suggestions;
}

function updateSuggestionStatus($id, $status, $reviewedBy, $reviewNotes = null) {
    $db = getDB();
    
    $stmt = $db->prepare('UPDATE suggestions SET status = ?, reviewed_by = ?, reviewed_at = CURRENT_TIMESTAMP, review_notes = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?');
    $stmt->bindValue(1, $status, SQLITE3_TEXT);
    $stmt->bindValue(2, $reviewedBy, SQLITE3_INTEGER);
    $stmt->bindValue(3, $reviewNotes, SQLITE3_TEXT);
    $stmt->bindValue(4, $id, SQLITE3_INTEGER);
    
    return $stmt->execute();
}

/**
 * Expenses CRUD Operations
 */
function createExpense($description, $amount, $category, $date, $vendor, $projectId, $submittedBy, $notes = null) {
    $db = getDB();
    
    $stmt = $db->prepare('INSERT INTO expenses (description, amount, category, date, vendor, project_id, submitted_by, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->bindValue(1, $description, SQLITE3_TEXT);
    $stmt->bindValue(2, $amount, SQLITE3_FLOAT);
    $stmt->bindValue(3, $category, SQLITE3_TEXT);
    $stmt->bindValue(4, $date, SQLITE3_TEXT);
    $stmt->bindValue(5, $vendor, SQLITE3_TEXT);
    $stmt->bindValue(6, $projectId, SQLITE3_INTEGER);
    $stmt->bindValue(7, $submittedBy, SQLITE3_INTEGER);
    $stmt->bindValue(8, $notes, SQLITE3_TEXT);
    
    return $stmt->execute();
}

function getExpenses($status = null, $category = null, $limit = 50) {
    $db = getDB();
    
    $sql = 'SELECT e.*, u1.first_name as submitter_first_name, u1.last_name as submitter_last_name, u2.first_name as approver_first_name, u2.last_name as approver_last_name, p.title as project_title FROM expenses e LEFT JOIN users u1 ON e.submitted_by = u1.id LEFT JOIN users u2 ON e.approved_by = u2.id LEFT JOIN projects p ON e.project_id = p.id';
    $conditions = [];
    $params = [];
    
    if ($status) {
        $conditions[] = 'e.status = ?';
        $params[] = $status;
    }
    
    if ($category) {
        $conditions[] = 'e.category = ?';
        $params[] = $category;
    }
    
    if (!empty($conditions)) {
        $sql .= ' WHERE ' . implode(' AND ', $conditions);
    }
    
    $sql .= ' ORDER BY e.created_at DESC LIMIT ?';
    $params[] = $limit;
    
    $stmt = $db->prepare($sql);
    foreach ($params as $i => $param) {
        $stmt->bindValue($i + 1, $param);
    }
    
    $result = $stmt->execute();
    $expenses = [];
    
    while ($expense = $result->fetchArray(SQLITE3_ASSOC)) {
        $expenses[] = $expense;
    }
    
    return $expenses;
}

function updateExpenseStatus($id, $status, $approvedBy = null) {
    $db = getDB();
    
    $stmt = $db->prepare('UPDATE expenses SET status = ?, approved_by = ?, approved_at = CURRENT_TIMESTAMP, updated_at = CURRENT_TIMESTAMP WHERE id = ?');
    $stmt->bindValue(1, $status, SQLITE3_TEXT);
    $stmt->bindValue(2, $approvedBy, SQLITE3_INTEGER);
    $stmt->bindValue(3, $id, SQLITE3_INTEGER);
    
    return $stmt->execute();
}

/**
 * Activities CRUD Operations
 */
function createActivity($title, $description, $hoursWorked, $mood, $category, $projectId, $userId, $activityDate) {
    $db = getDB();
    
    $stmt = $db->prepare('INSERT INTO activities (title, description, hours_worked, mood, category, project_id, user_id, activity_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->bindValue(1, $title, SQLITE3_TEXT);
    $stmt->bindValue(2, $description, SQLITE3_TEXT);
    $stmt->bindValue(3, $hoursWorked, SQLITE3_FLOAT);
    $stmt->bindValue(4, $mood, SQLITE3_TEXT);
    $stmt->bindValue(5, $category, SQLITE3_TEXT);
    $stmt->bindValue(6, $projectId, SQLITE3_INTEGER);
    $stmt->bindValue(7, $userId, SQLITE3_INTEGER);
    $stmt->bindValue(8, $activityDate, SQLITE3_TEXT);
    
    return $stmt->execute();
}

function getActivities($userId = null, $projectId = null, $limit = 50) {
    $db = getDB();
    
    $sql = 'SELECT a.*, u.first_name, u.last_name, p.title as project_title FROM activities a LEFT JOIN users u ON a.user_id = u.id LEFT JOIN projects p ON a.project_id = p.id';
    $conditions = [];
    $params = [];
    
    if ($userId) {
        $conditions[] = 'a.user_id = ?';
        $params[] = $userId;
    }
    
    if ($projectId) {
        $conditions[] = 'a.project_id = ?';
        $params[] = $projectId;
    }
    
    if (!empty($conditions)) {
        $sql .= ' WHERE ' . implode(' AND ', $conditions);
    }
    
    $sql .= ' ORDER BY a.created_at DESC LIMIT ?';
    $params[] = $limit;
    
    $stmt = $db->prepare($sql);
    foreach ($params as $i => $param) {
        $stmt->bindValue($i + 1, $param);
    }
    
    $result = $stmt->execute();
    $activities = [];
    
    while ($activity = $result->fetchArray(SQLITE3_ASSOC)) {
        $activities[] = $activity;
    }
    
    return $activities;
}

/**
 * Learning Resources CRUD Operations
 */
function createLearningResource($title, $description, $category, $resourceType, $url, $fileUrl, $createdBy) {
    $db = getDB();
    
    $stmt = $db->prepare('INSERT INTO learning_resources (title, description, category, resource_type, url, file_url, created_by) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->bindValue(1, $title, SQLITE3_TEXT);
    $stmt->bindValue(2, $description, SQLITE3_TEXT);
    $stmt->bindValue(3, $category, SQLITE3_TEXT);
    $stmt->bindValue(4, $resourceType, SQLITE3_TEXT);
    $stmt->bindValue(5, $url, SQLITE3_TEXT);
    $stmt->bindValue(6, $fileUrl, SQLITE3_TEXT);
    $stmt->bindValue(7, $createdBy, SQLITE3_INTEGER);
    
    return $stmt->execute();
}

function getLearningResources($category = null, $limit = 50) {
    $db = getDB();
    
    $sql = 'SELECT lr.*, u.first_name, u.last_name FROM learning_resources lr LEFT JOIN users u ON lr.created_by = u.id WHERE lr.is_active = 1';
    
    if ($category) {
        $sql .= ' AND lr.category = ?';
    }
    
    $sql .= ' ORDER BY lr.created_at DESC LIMIT ?';
    
    $stmt = $db->prepare($sql);
    if ($category) {
        $stmt->bindValue(1, $category, SQLITE3_TEXT);
        $stmt->bindValue(2, $limit, SQLITE3_INTEGER);
    } else {
        $stmt->bindValue(1, $limit, SQLITE3_INTEGER);
    }
    
    $result = $stmt->execute();
    $resources = [];
    
    while ($resource = $result->fetchArray(SQLITE3_ASSOC)) {
        $resources[] = $resource;
    }
    
    return $resources;
}

/**
 * Statistics Functions
 */
function getDashboardStats($role) {
    $db = getDB();
    $stats = [];
    
    // Get counts for different entities
    $result = $db->query('SELECT COUNT(*) as count FROM projects');
    $stats['total_projects'] = $result->fetchArray(SQLITE3_ASSOC)['count'];
    
    $result = $db->query('SELECT COUNT(*) as count FROM suggestions');
    $stats['total_suggestions'] = $result->fetchArray(SQLITE3_ASSOC)['count'];
    
    $result = $db->query('SELECT COUNT(*) as count FROM expenses');
    $stats['total_expenses'] = $result->fetchArray(SQLITE3_ASSOC)['count'];
    
    $result = $db->query('SELECT COUNT(*) as count FROM activities');
    $stats['total_activities'] = $result->fetchArray(SQLITE3_ASSOC)['count'];
    
    // Get recent items
    $stats['recent_projects'] = getProjects(null, null, 5);
    $stats['recent_suggestions'] = getSuggestions(null, 5);
    $stats['recent_expenses'] = getExpenses(null, null, 5);
    
    return $stats;
}
?> 