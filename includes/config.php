<?php
// Database configuration
define('DB_PATH', __DIR__ . '/../data/foz_da_cova.db');

// App configuration
define('APP_NAME', 'Foz da Cova Dashboard');
define('APP_VERSION', '1.0.0');

// Session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Set to 1 for HTTPS

// Error reporting (set to 0 for production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
function getDB() {
    static $db = null;
    if ($db === null) {
        try {
            $db = new SQLite3(DB_PATH);
            $db->enableExceptions(true);
            
            // Set up foreign keys
            $db->exec('PRAGMA foreign_keys = ON');
            
        } catch (Exception $e) {
            die('Database connection failed: ' . $e->getMessage());
        }
    }
    return $db;
}

// Helper function to escape output
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Helper function to redirect
function redirect($url) {
    header('Location: ' . $url);
    exit;
}

// Helper function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Helper function to get current user role
function getCurrentUserRole() {
    return $_SESSION['role'] ?? null;
}

// Helper function to check if user has role
function hasRole($role) {
    return getCurrentUserRole() === $role;
}
?> 