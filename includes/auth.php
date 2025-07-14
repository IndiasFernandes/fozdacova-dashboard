<?php
require_once 'config.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Login function
 */
function login($username, $password) {
    $db = getDB();
    
    $stmt = $db->prepare('SELECT * FROM users WHERE username = ? AND is_active = 1');
    $stmt->bindValue(1, $username, SQLITE3_TEXT);
    $result = $stmt->execute();
    
    if ($user = $result->fetchArray(SQLITE3_ASSOC)) {
        // In production, use password_verify() with proper hashing
        if ($password === $user['password_hash'] || password_verify($password, $user['password_hash'])) {
            // Set session data
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['avatar_initials'] = $user['avatar_initials'];
            
            return true;
        }
    }
    
    return false;
}

/**
 * Logout function
 */
function logout() {
    // Unset all session variables
    $_SESSION = array();
    
    // Destroy the session
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }
}

/**
 * Check if user is authenticated
 */
function requireAuth() {
    if (!isLoggedIn()) {
        redirect('/login.php');
    }
}

/**
 * Check if user has specific role
 */
function requireRole($role) {
    requireAuth();
    
    if (!hasRole($role)) {
        redirect('/login.php');
    }
}

/**
 * Check if user has any of the specified roles
 */
function requireAnyRole($roles) {
    requireAuth();
    
    $userRole = getCurrentUserRole();
    if (!in_array($userRole, $roles)) {
        redirect('/login.php');
    }
}

/**
 * Get current user data
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'],
        'role' => $_SESSION['role'],
        'first_name' => $_SESSION['first_name'],
        'last_name' => $_SESSION['last_name'],
        'avatar_initials' => $_SESSION['avatar_initials']
    ];
}

/**
 * Create user (for setup)
 */
function createUser($username, $password, $role, $firstName, $lastName, $email, $avatarInitials) {
    $db = getDB();
    
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $db->prepare('INSERT INTO users (username, password_hash, role, first_name, last_name, email, avatar_initials) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->bindValue(1, $username, SQLITE3_TEXT);
    $stmt->bindValue(2, $hashedPassword, SQLITE3_TEXT);
    $stmt->bindValue(3, $role, SQLITE3_TEXT);
    $stmt->bindValue(4, $firstName, SQLITE3_TEXT);
    $stmt->bindValue(5, $lastName, SQLITE3_TEXT);
    $stmt->bindValue(6, $email, SQLITE3_TEXT);
    $stmt->bindValue(7, $avatarInitials, SQLITE3_TEXT);
    
    return $stmt->execute();
}

/**
 * Get user by ID
 */
function getUserById($id) {
    $db = getDB();
    
    $stmt = $db->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->bindValue(1, $id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    
    return $result->fetchArray(SQLITE3_ASSOC);
}

/**
 * Get all users
 */
function getAllUsers() {
    $db = getDB();
    
    $result = $db->query('SELECT * FROM users ORDER BY created_at DESC');
    $users = [];
    
    while ($user = $result->fetchArray(SQLITE3_ASSOC)) {
        $users[] = $user;
    }
    
    return $users;
}
?> 