<?php
require_once 'includes/config.php';

header('Content-Type: text/plain');

echo "=== Foz da Cova Dashboard Health Check ===\n\n";

// PHP Version
echo "PHP Version: " . phpversion() . "\n";

// SQLite Support
if (extension_loaded('sqlite3')) {
    echo "SQLite3: ✓ Available\n";
} else {
    echo "SQLite3: ✗ Not available\n";
}

// Database Connection
try {
    $db = new SQLite3(DB_PATH);
    echo "Database Connection: ✓ Success\n";
} catch (Exception $e) {
    echo "Database Connection: ✗ Failed - " . $e->getMessage() . "\n";
}

// File Permissions
$dataDir = dirname(DB_PATH);
if (is_dir($dataDir)) {
    echo "Data Directory: ✓ Exists\n";
    if (is_writable($dataDir)) {
        echo "Data Directory Permissions: ✓ Writable\n";
    } else {
        echo "Data Directory Permissions: ✗ Not writable\n";
    }
} else {
    echo "Data Directory: ✗ Does not exist\n";
}

// Session Support
if (function_exists('session_start')) {
    echo "Session Support: ✓ Available\n";
} else {
    echo "Session Support: ✗ Not available\n";
}

// Required Extensions
$required_extensions = ['sqlite3', 'json', 'mbstring'];
foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "$ext Extension: ✓ Available\n";
    } else {
        echo "$ext Extension: ✗ Not available\n";
    }
}

// Memory Limit
echo "Memory Limit: " . ini_get('memory_limit') . "\n";

// Upload Settings
echo "Upload Max Filesize: " . ini_get('upload_max_filesize') . "\n";
echo "Post Max Size: " . ini_get('post_max_size') . "\n";

// Server Information
echo "Server Software: " . $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' . "\n";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown' . "\n";

echo "\n=== Health Check Complete ===\n";
?> 