# Foz da Cova Dashboard - Implementation Task List

## Overview
Transform the current HTML dashboard system into a proper, minimal, and efficient web application that runs flawlessly on cPanel with SQLite database and template-based architecture.

---

## Phase 1: System Architecture & Setup (Week 1)

### 1.1 Project Structure Setup
- [ ] **Create proper directory structure**
  ```
  foz-da-cova/
  ├── public/                 # Web root (cPanel public_html)
  │   ├── index.php          # Main entry point
  │   ├── login.php          # Authentication
  │   ├── assets/            # CSS, JS, images
  │   └── uploads/           # File uploads
  ├── includes/              # PHP includes
  │   ├── config.php         # Database & app config
  │   ├── auth.php           # Authentication logic
  │   ├── functions.php      # Utility functions
  │   └── templates/         # Template files
  │       ├── header.php
  │       ├── footer.php
  │       ├── sidebar-admin.php
  │       ├── sidebar-stewart.php
  │       └── sidebar-wwoofer.php
  ├── data/                  # Database & data files
  │   └── foz_da_cova.db    # SQLite database
  └── admin/                 # Admin-only files
      └── setup.php          # Database setup
  ```

### 1.2 Database Setup
- [ ] **Create minimal SQLite database schema**
  ```sql
  -- Core tables only
  CREATE TABLE users (
      id INTEGER PRIMARY KEY,
      username TEXT UNIQUE,
      password_hash TEXT,
      role TEXT CHECK(role IN ('admin', 'stewart', 'wwoofer')),
      name TEXT,
      email TEXT,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  );

  CREATE TABLE projects (
      id INTEGER PRIMARY KEY,
      title TEXT,
      description TEXT,
      category TEXT,
      status TEXT DEFAULT 'open',
      created_by INTEGER,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (created_by) REFERENCES users(id)
  );

  CREATE TABLE suggestions (
      id INTEGER PRIMARY KEY,
      title TEXT,
      description TEXT,
      status TEXT DEFAULT 'new',
      submitted_by INTEGER,
      reviewed_by INTEGER,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (submitted_by) REFERENCES users(id),
      FOREIGN KEY (reviewed_by) REFERENCES users(id)
  );

  CREATE TABLE expenses (
      id INTEGER PRIMARY KEY,
      description TEXT,
      amount DECIMAL(10,2),
      category TEXT,
      date DATE,
      submitted_by INTEGER,
      status TEXT DEFAULT 'pending',
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (submitted_by) REFERENCES users(id)
  );
  ```

### 1.3 Authentication System
- [ ] **Create simple session-based auth**
  - [ ] `includes/auth.php` - Login/logout functions
  - [ ] `includes/config.php` - Database connection
  - [ ] `login.php` - Login form
  - [ ] Session management for role-based access

### 1.4 Template System
- [ ] **Create reusable template components**
  - [ ] `includes/templates/header.php` - Common header
  - [ ] `includes/templates/footer.php` - Common footer
  - [ ] `includes/templates/sidebar-admin.php` - Admin navigation
  - [ ] `includes/templates/sidebar-stewart.php` - Stewart navigation
  - [ ] `includes/templates/sidebar-wwoofer.php` - WWOOFer navigation

---

## Phase 2: Core Pages Implementation (Week 2)

### 2.1 Authentication & Access Control
- [ ] **Implement role-based routing**
  - [ ] `public/index.php` - Main router
  - [ ] Role verification on each page
  - [ ] Redirect unauthorized access

### 2.2 Dashboard Pages
- [ ] **Admin Dashboard**
  - [ ] `public/admin/dashboard.php` - Admin overview
  - [ ] `public/admin/projects.php` - Project management
  - [ ] `public/admin/expenses.php` - Expense tracking
  - [ ] `public/admin/suggestions.php` - Ideas review
  - [ ] `public/admin/users.php` - User management

- [ ] **Stewart Dashboard**
  - [ ] `public/stewart/dashboard.php` - Stewart overview
  - [ ] `public/stewart/projects.php` - Project coordination
  - [ ] `public/stewart/expenses.php` - Resource flow
  - [ ] `public/stewart/suggestions.php` - Ideas review
  - [ ] `public/stewart/resources.php` - Resource requests

- [ ] **WWOOFer Dashboard**
  - [ ] `public/wwoofer/dashboard.php` - WWOOFer overview
  - [ ] `public/wwoofer/projects.php` - Join missions
  - [ ] `public/wwoofer/activities.php` - Log activities
  - [ ] `public/wwoofer/suggestions.php` - Submit ideas
  - [ ] `public/wwoofer/learning.php` - Learning resources

### 2.3 Form Processing
- [ ] **Create form handlers**
  - [ ] `includes/handlers/project-handler.php`
  - [ ] `includes/handlers/expense-handler.php`
  - [ ] `includes/handlers/suggestion-handler.php`
  - [ ] `includes/handlers/user-handler.php`

---

## Phase 3: Database Integration (Week 3)

### 3.1 Database Functions
- [ ] **Create database helper functions**
  - [ ] `includes/db.php` - Database connection & queries
  - [ ] CRUD operations for all entities
  - [ ] Prepared statements for security

### 3.2 Data Migration
- [ ] **Convert static data to database**
  - [ ] Import sample projects
  - [ ] Import sample users
  - [ ] Import sample suggestions
  - [ ] Import sample expenses

### 3.3 Search & Filtering
- [ ] **Implement dynamic filtering**
  - [ ] Project filtering by status/category
  - [ ] Expense filtering by date/category
  - [ ] Suggestion filtering by status
  - [ ] User search functionality

---

## Phase 4: Security & Optimization (Week 4)

### 4.1 Security Implementation
- [ ] **Add security measures**
  - [ ] SQL injection prevention
  - [ ] XSS protection
  - [ ] CSRF tokens
  - [ ] Input validation
  - [ ] File upload security

### 4.2 Performance Optimization
- [ ] **Optimize for speed**
  - [ ] Database indexing
  - [ ] CSS/JS minification
  - [ ] Image optimization
  - [ ] Caching implementation
  - [ ] Lazy loading

### 4.3 Error Handling
- [ ] **Implement error management**
  - [ ] Custom error pages
  - [ ] Logging system
  - [ ] User-friendly error messages
  - [ ] Debug mode for development

---

## Phase 5: cPanel Deployment (Week 5)

### 5.1 cPanel Setup
- [ ] **Configure cPanel environment**
  - [ ] Set up subdomain or directory
  - [ ] Configure PHP version (7.4+)
  - [ ] Enable SQLite support
  - [ ] Set proper file permissions

### 5.2 File Organization
- [ ] **Organize for cPanel**
  - [ ] Move to public_html structure
  - [ ] Secure sensitive files outside web root
  - [ ] Configure .htaccess for clean URLs
  - [ ] Set up proper redirects

### 5.3 Database Setup
- [ ] **Deploy database**
  - [ ] Create SQLite database on server
  - [ ] Import schema and sample data
  - [ ] Test database connections
  - [ ] Backup procedures

---

## Phase 6: Testing & Polish (Week 6)

### 6.1 Testing
- [ ] **Comprehensive testing**
  - [ ] User authentication testing
  - [ ] Role-based access testing
  - [ ] Form submission testing
  - [ ] Database operations testing
  - [ ] Mobile responsiveness testing

### 6.2 User Experience
- [ ] **Polish user interface**
  - [ ] Loading states
  - [ ] Success/error messages
  - [ ] Form validation feedback
  - [ ] Responsive design fixes
  - [ ] Accessibility improvements

### 6.3 Documentation
- [ ] **Create documentation**
  - [ ] User manual for each role
  - [ ] Admin setup guide
  - [ ] Database schema documentation
  - [ ] Deployment instructions

---

## Technical Specifications

### Database Schema (Minimal)
```sql
-- Users table (all roles)
users (id, username, password_hash, role, name, email, created_at)

-- Projects (missions)
projects (id, title, description, category, status, created_by, created_at)

-- Ideas & suggestions (unified ticket system)
suggestions (id, title, description, status, submitted_by, reviewed_by, created_at)

-- Expenses (resource flow)
expenses (id, description, amount, category, date, submitted_by, status, created_at)

-- Activities (WWOOFer work logging)
activities (id, title, description, hours, mood, user_id, project_id, created_at)
```

### File Structure (cPanel Ready)
```
public_html/
├── index.php              # Main router
├── login.php              # Authentication
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── admin/                 # Admin pages
├── stewart/               # Stewart pages
├── wwoofer/               # WWOOFer pages
└── includes/              # PHP includes
    ├── config.php
    ├── auth.php
    ├── db.php
    └── templates/
```

### Security Requirements
- [ ] Session-based authentication
- [ ] Role-based access control
- [ ] SQL injection prevention
- [ ] XSS protection
- [ ] Input validation
- [ ] Secure file uploads

### Performance Requirements
- [ ] Page load < 2 seconds
- [ ] Mobile-first responsive design
- [ ] Minimal JavaScript usage
- [ ] Optimized database queries
- [ ] Efficient caching

---

## Implementation Priority

### High Priority (Must Have)
1. Authentication system
2. Role-based access control
3. Basic CRUD operations
4. Template system
5. Database integration

### Medium Priority (Should Have)
1. Search and filtering
2. File uploads
3. Advanced security
4. Performance optimization
5. Error handling

### Low Priority (Nice to Have)
1. Advanced reporting
2. Email notifications
3. API endpoints
4. Advanced analytics
5. Mobile app integration

---

## Success Criteria

### Functional Requirements
- [ ] All three roles can access their dashboards
- [ ] Users can only access role-appropriate pages
- [ ] All forms work and save to database
- [ ] Search and filtering work correctly
- [ ] Mobile responsive on all devices

### Performance Requirements
- [ ] Pages load in under 2 seconds
- [ ] Database queries are optimized
- [ ] No JavaScript errors
- [ ] Works on all modern browsers

### Security Requirements
- [ ] No SQL injection vulnerabilities
- [ ] No XSS vulnerabilities
- [ ] Proper session management
- [ ] Secure file uploads

### Deployment Requirements
- [ ] Works on standard cPanel hosting
- [ ] Easy to backup and restore
- [ ] Minimal server requirements
- [ ] Clear deployment instructions

---

## Timeline Summary

- **Week 1**: System architecture, database setup, authentication
- **Week 2**: Core pages, templates, form processing
- **Week 3**: Database integration, data migration, search/filtering
- **Week 4**: Security, optimization, error handling
- **Week 5**: cPanel deployment, file organization
- **Week 6**: Testing, polish, documentation

**Total Estimated Time**: 6 weeks for complete implementation

---

*This task list ensures a minimal, efficient, and secure dashboard system that runs flawlessly on cPanel with proper role-based access control and template-based architecture.* 