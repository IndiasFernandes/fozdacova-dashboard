# Foz da Cova - Simple Implementation Plan

## Overview
Super simple approach: Pure HTML/PHP, SQLite database, no frameworks. Just copy-paste and it works.

---

## 🎯 **How Pages Work (Simple Navigation)**

### **Current Structure (Keep This)**
```
dashboards/
├── admin/
│   ├── index.html          # Dashboard
│   ├── projects.html       # Projects
│   ├── expenses.html       # Expenses
│   └── ideas.html          # Ideas
├── stewart/
│   ├── index.html          # Dashboard
│   ├── projects.html       # Projects
│   ├── costs.html          # Resource Flow
│   └── ideas.html          # Ideas
└── wwoofer/
    ├── index.html          # Dashboard
    ├── projects.html       # Join Missions
    ├── activities.html     # Log Work
    └── suggestions.html    # Submit Ideas
```

### **Simple Navigation Flow**
1. **Login** → `login.html`
2. **Role Check** → Redirect to appropriate dashboard
3. **Sidebar Links** → Direct HTML page navigation
4. **No React, No SPA** → Just regular HTML pages

---

## 🚀 **Super Simple Implementation (3 Steps)**

### **Step 1: Add PHP to Existing HTML (1 Day)**
- [ ] **Convert each HTML page to PHP**
  ```php
  <?php
  session_start();
  // Check if user is logged in
  if (!isset($_SESSION['user_id'])) {
      header('Location: ../login.html');
      exit;
  }
  // Check role permissions
  if ($_SESSION['role'] !== 'admin') {
      header('Location: ../login.html');
      exit;
  }
  ?>
  <!DOCTYPE html>
  <!-- Rest of existing HTML stays the same -->
  ```

### **Step 2: Simple Database (1 Day)**
- [ ] **Create SQLite database**
  ```sql
  -- Just 3 tables
  CREATE TABLE users (
      id INTEGER PRIMARY KEY,
      username TEXT,
      password TEXT,
      role TEXT
  );

  CREATE TABLE suggestions (
      id INTEGER PRIMARY KEY,
      title TEXT,
      description TEXT,
      status TEXT,
      user_id INTEGER
  );

  CREATE TABLE projects (
      id INTEGER PRIMARY KEY,
      title TEXT,
      description TEXT,
      status TEXT
  );
  ```

### **Step 3: Simple Forms (1 Day)**
- [ ] **Add PHP form processing**
  ```php
  <?php
  if ($_POST) {
      $title = $_POST['title'];
      $description = $_POST['description'];
      // Save to database
      $sql = "INSERT INTO suggestions (title, description, user_id) VALUES (?, ?, ?)";
      // Execute query
  }
  ?>
  ```

---

## 📁 **File Structure (Minimal)**

```
foz-da-cova/
├── public_html/           # cPanel web root
│   ├── index.php         # Main entry
│   ├── login.php         # Login page
│   ├── dashboards/       # All dashboard pages
│   │   ├── admin/
│   │   ├── stewart/
│   │   └── wwoofer/
│   ├── assets/           # CSS, JS, images
│   └── data/            # SQLite database
└── includes/            # PHP files
    ├── config.php       # Database connection
    ├── auth.php         # Login/logout
    └── functions.php    # Helper functions
```

---

## 🔧 **Simple Database Setup**

### **config.php**
```php
<?php
$db = new SQLite3('data/foz_da_cova.db');
?>
```

### **auth.php**
```php
<?php
function login($username, $password) {
    global $db;
    $stmt = $db->prepare('SELECT * FROM users WHERE username = ? AND password = ?');
    $stmt->bindValue(1, $username);
    $stmt->bindValue(2, $password);
    $result = $stmt->execute();
    return $result->fetchArray();
}
?>
```

---

## 📄 **Page Conversion Examples**

### **Before (HTML)**
```html
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <!-- Content -->
</body>
</html>
```

### **After (PHP)**
```php
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <!-- Same content, just add PHP at top -->
</body>
</html>
```

---

## 🎯 **Implementation Steps (3 Days Total)**

### **Day 1: Setup & Authentication**
- [ ] **Create database file**
- [ ] **Add PHP to login page**
- [ ] **Create session management**
- [ ] **Test login/logout**

### **Day 2: Convert Pages**
- [ ] **Add PHP header to all HTML pages**
- [ ] **Add role checking**
- [ ] **Test navigation between pages**
- [ ] **Verify access control**

### **Day 3: Add Database Features**
- [ ] **Convert forms to save to database**
- [ ] **Add data display from database**
- [ ] **Test all CRUD operations**
- [ ] **Deploy to cPanel**

---

## 🚀 **Deployment (cPanel)**

### **Upload Files**
1. Upload all files to `public_html/`
2. Create `data/` folder for database
3. Set permissions (755 for folders, 644 for files)

### **Database Setup**
1. Create SQLite database file
2. Import sample data
3. Test connections

### **Test Everything**
1. Test login with each role
2. Test page access permissions
3. Test form submissions
4. Test database operations

---

## ✅ **What We Keep Simple**

### **No Complex Features**
- ❌ No React/JavaScript frameworks
- ❌ No complex routing
- ❌ No API endpoints
- ❌ No advanced caching

### **Simple Features**
- ✅ Basic login/logout
- ✅ Role-based page access
- ✅ Simple forms that save to database
- ✅ Basic data display
- ✅ Mobile responsive (existing CSS)

---

## 🎯 **Success Criteria**

### **Functional**
- [ ] Users can log in
- [ ] Each role sees only their pages
- [ ] Forms save data to database
- [ ] Data displays correctly
- [ ] Mobile responsive

### **Technical**
- [ ] Works on standard cPanel hosting
- [ ] No external dependencies
- [ ] Fast page loads
- [ ] Secure (basic protection)

---

## 📋 **Quick Task List**

### **Phase 1: Setup (Day 1)**
- [ ] Create SQLite database
- [ ] Add PHP to login page
- [ ] Create session management
- [ ] Test authentication

### **Phase 2: Pages (Day 2)**
- [ ] Add PHP headers to all HTML pages
- [ ] Add role checking
- [ ] Test navigation
- [ ] Verify access control

### **Phase 3: Database (Day 3)**
- [ ] Convert forms to save data
- [ ] Display data from database
- [ ] Test all features
- [ ] Deploy to cPanel

---

## 🎯 **Navigation Flow**

```
login.php → Check Role → Redirect to Dashboard
    ↓
admin/index.php → Sidebar Links → Other Admin Pages
stewart/index.php → Sidebar Links → Other Stewart Pages  
wwoofer/index.php → Sidebar Links → Other WWOOFer Pages
```

**Simple, direct HTML navigation - no React needed!**

---

*This approach keeps everything super simple: Pure HTML/PHP, minimal database, direct page navigation. Perfect for cPanel hosting with zero complexity.* 