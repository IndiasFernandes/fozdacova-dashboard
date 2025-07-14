<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

// Logout the user
logout();

// Redirect to login page
redirect('/login.php');
?> 