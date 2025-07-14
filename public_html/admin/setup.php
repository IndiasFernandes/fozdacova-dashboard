<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/db.php';

// Check if database already exists
if (file_exists(DB_PATH)) {
    echo "Database already exists. Delete it manually if you want to recreate.\n";
    exit;
}

try {
    // Create database directory if it doesn't exist
    $dbDir = dirname(DB_PATH);
    if (!is_dir($dbDir)) {
        mkdir($dbDir, 0755, true);
    }
    
    // Create database connection
    $db = new SQLite3(DB_PATH);
    $db->enableExceptions(true);
    
    echo "Creating database schema...\n";
    
    // Create tables
    $db->exec("
        CREATE TABLE users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE NOT NULL,
            password_hash TEXT NOT NULL,
            role TEXT NOT NULL CHECK(role IN ('admin', 'stewart', 'wwoofer')),
            first_name TEXT,
            last_name TEXT,
            email TEXT,
            avatar_initials TEXT,
            is_active BOOLEAN DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    $db->exec("
        CREATE TABLE projects (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            description TEXT,
            category TEXT CHECK(category IN ('water-systems', 'natural-building', 'gardening', 'land-maintenance', 'housekeeping', 'community', 'education', 'sustainability')),
            status TEXT DEFAULT 'open' CHECK(status IN ('open', 'in-progress', 'completed', 'cancelled')),
            priority TEXT DEFAULT 'normal' CHECK(priority IN ('low', 'normal', 'high', 'urgent')),
            max_participants INTEGER,
            current_participants INTEGER DEFAULT 0,
            location TEXT,
            start_date DATE,
            end_date DATE,
            created_by INTEGER,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (created_by) REFERENCES users(id)
        )
    ");
    
    $db->exec("
        CREATE TABLE suggestions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            description TEXT,
            status TEXT DEFAULT 'new' CHECK(status IN ('new', 'reviewing', 'approved', 'rejected')),
            submitted_by INTEGER NOT NULL,
            reviewed_by INTEGER,
            reviewed_at TIMESTAMP,
            review_notes TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (submitted_by) REFERENCES users(id),
            FOREIGN KEY (reviewed_by) REFERENCES users(id)
        )
    ");
    
    $db->exec("
        CREATE TABLE expenses (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            description TEXT NOT NULL,
            amount DECIMAL(10,2) NOT NULL,
            category TEXT CHECK(category IN ('tools', 'materials', 'utilities', 'maintenance', 'events', 'admin', 'other')),
            date DATE NOT NULL,
            vendor TEXT,
            project_id INTEGER,
            submitted_by INTEGER NOT NULL,
            status TEXT DEFAULT 'pending' CHECK(status IN ('pending', 'approved', 'rejected')),
            approved_by INTEGER,
            approved_at TIMESTAMP,
            notes TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (submitted_by) REFERENCES users(id),
            FOREIGN KEY (approved_by) REFERENCES users(id),
            FOREIGN KEY (project_id) REFERENCES projects(id)
        )
    ");
    
    $db->exec("
        CREATE TABLE activities (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            description TEXT,
            hours_worked DECIMAL(4,2),
            mood TEXT CHECK(mood IN ('great', 'good', 'okay', 'difficult')),
            category TEXT CHECK(category IN ('water-systems', 'natural-building', 'gardening', 'land-maintenance', 'housekeeping', 'community', 'education', 'sustainability')),
            project_id INTEGER,
            user_id INTEGER NOT NULL,
            activity_date DATE NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id),
            FOREIGN KEY (project_id) REFERENCES projects(id)
        )
    ");
    
    $db->exec("
        CREATE TABLE project_participants (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            project_id INTEGER NOT NULL,
            user_id INTEGER NOT NULL,
            status TEXT DEFAULT 'registered' CHECK(status IN ('registered', 'confirmed', 'attended', 'no-show')),
            hours_contributed DECIMAL(5,2) DEFAULT 0,
            joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (project_id) REFERENCES projects(id),
            FOREIGN KEY (user_id) REFERENCES users(id)
        )
    ");
    
    $db->exec("
        CREATE TABLE learning_resources (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            description TEXT,
            category TEXT CHECK(category IN ('guides', 'contacts', 'maps', 'notion', 'other')),
            resource_type TEXT CHECK(resource_type IN ('document', 'link', 'contact', 'map', 'notion-page')),
            url TEXT,
            file_url TEXT,
            is_active BOOLEAN DEFAULT 1,
            created_by INTEGER,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (created_by) REFERENCES users(id)
        )
    ");
    
    echo "Creating sample users...\n";
    
    // Create sample users
    createUser('admin', 'admin123', 'admin', 'Admin', 'User', 'admin@fozdacova.com', 'AD');
    createUser('maria', 'maria123', 'stewart', 'Maria', 'Silva', 'maria@fozdacova.com', 'MS');
    createUser('joao', 'joao123', 'wwoofer', 'João', 'Santos', 'joao@fozdacova.com', 'JS');
    createUser('ana', 'ana123', 'wwoofer', 'Ana', 'Costa', 'ana@fozdacova.com', 'AC');
    
    echo "Creating sample projects...\n";
    
    // Create sample projects
    createProject('Water System Maintenance', 'Maintain and improve water collection systems', 'water-systems', 'in-progress', 'high', 5, 'Main water tank area', '2024-02-01', '2024-03-01', 1);
    createProject('Spring Garden Planting', 'Plant seasonal vegetables and herbs', 'gardening', 'open', 'normal', 8, 'Community garden', '2024-03-01', '2024-04-01', 2);
    createProject('Community Kitchen Renovation', 'Improve kitchen facilities', 'housekeeping', 'open', 'medium', 3, 'Main kitchen', '2024-04-01', '2024-05-01', 1);
    
    echo "Creating sample suggestions...\n";
    
    // Create sample suggestions
    createSuggestion('Better compost system', 'Improve compost organization with more bins', 3);
    createSuggestion('Weekly community dinner', 'Have weekly community cooking sessions', 4);
    createSuggestion('More water containers', 'Add storage containers for dry season', 3);
    
    echo "Creating sample expenses...\n";
    
    // Create sample expenses
    createExpense('Garden tools set', 85.00, 'tools', '2024-02-15', 'Local hardware store', 2, 2, 'Essential tools for garden work');
    createExpense('Water system filters', 45.00, 'maintenance', '2024-02-12', 'Plumbing supplies', 1, 2, 'Replacement filters');
    createExpense('Compost materials', 32.50, 'materials', '2024-02-10', 'Garden center', 2, 2, 'Organic compost for garden');
    
    echo "Creating sample activities...\n";
    
    // Create sample activities
    createActivity('Watered garden plants', 'Watered all vegetables in the main garden', 2.5, 'good', 'gardening', 2, 3, '2024-02-15');
    createActivity('Fixed water pipe', 'Repaired leaking pipe in main system', 3.0, 'great', 'water-systems', 1, 3, '2024-02-14');
    createActivity('Cleaned kitchen', 'Deep cleaned community kitchen', 2.0, 'okay', 'housekeeping', 3, 4, '2024-02-13');
    
    echo "Creating sample learning resources...\n";
    
    // Create sample learning resources
    createLearningResource('Stewart Handbook', 'Complete guide for Stewart role', 'guides', 'document', null, '/uploads/stewart-handbook.pdf', 1);
    createLearningResource('Emergency Contacts', 'Essential emergency contacts', 'contacts', 'contact', null, null, 1);
    createLearningResource('Property Maps', 'Detailed maps of the property', 'maps', 'map', null, '/uploads/property-maps.pdf', 1);
    createLearningResource('Community Hub', 'Central Notion workspace', 'notion', 'notion-page', 'https://notion.so/community-hub', null, 1);
    
    echo "Database setup completed successfully!\n";
    echo "You can now log in with:\n";
    echo "- Admin: admin / admin123\n";
    echo "- Stewart: maria / maria123\n";
    echo "- WWOOFer: joao / joao123\n";
    echo "- WWOOFer: ana / ana123\n";
    
} catch (Exception $e) {
    echo "Error setting up database: " . $e->getMessage() . "\n";
}
?> 