<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

// If not logged in, redirect to login
if (!isLoggedIn()) {
    redirect('/login.php');
}

// Get user role and redirect to appropriate dashboard
$role = getCurrentUserRole();

switch ($role) {
    case 'admin':
        redirect('/admin/dashboard.php');
        break;
    case 'stewart':
        redirect('/stewart/dashboard.php');
        break;
    case 'wwoofer':
        redirect('/wwoofer/dashboard.php');
        break;
    default:
        // Invalid role, logout and redirect to login
        logout();
        redirect('/login.php');
}
?> 