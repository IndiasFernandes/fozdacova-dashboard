# Foz da Cova Community Land Project - Website Structure

## Table of Contents
1. [Site Map](#site-map)
2. [Database Schema](#database-schema)
3. [Design System](#design-system)
4. [Performance Guidelines](#performance-guidelines)
5. [Future Features](#future-features)

---

## Site Map

### Public Area
```
/ (Home)
├── Hero section with mission
├── Featured projects (3-4 cards)
├── Quick stats (members, acres, years)
└── Call-to-action buttons

/about
├── Community story and history
├── Land description and features
├── Values and principles
├── Photo gallery
└── Location and access info

/join
├── Application process steps
├── Requirements and expectations
├── Benefits of membership
├── FAQ section
└── Application form

/projects
├── Filterable project grid
├── Project categories (construction, gardening, events)
├── Status indicators (open, in-progress, completed)
└── Project details modal/popup

/calendar
├── Monthly view with events
├── Event categories (work days, meetings, social)
├── Event details on click
└── Add to calendar functionality

/contact
├── Contact form with validation
├── Location and directions
├── Social media links
└── Emergency contact info
```

### Admin Area
```
/admin (Dashboard)
├── Overview and quick stats
├── Recent activities
├── Quick actions
└── System status

/admin/projects
├── Project CRUD operations
├── Project status management
├── Participant tracking
└── Project analytics

/admin/applicants
├── Application review interface
├── Status management (pending, approved, rejected)
├── Communication tools
└── Application analytics

/admin/expenses
├── Expense tracking
├── Category management
├── Financial reports
└── Budget overview

/admin/settings
├── Site configuration
├── User management
├── Content management
└── System preferences
```

---

## Database Schema

### Core Tables

#### Projects Table
```sql
CREATE TABLE projects (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    description TEXT,
    category TEXT CHECK(category IN ('water-systems', 'natural-building', 'gardening', 'land-maintenance', 'housekeeping', 'community', 'education', 'sustainability')),
    status TEXT DEFAULT 'open' CHECK(status IN ('open', 'in-progress', 'completed', 'cancelled')),
    start_date DATE,
    end_date DATE,
    max_participants INTEGER,
    current_participants INTEGER DEFAULT 0,
    location TEXT,
    required_skills TEXT,
    priority TEXT DEFAULT 'normal' CHECK(priority IN ('low', 'normal', 'high', 'urgent')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### Events Table
```sql
CREATE TABLE events (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    description TEXT,
    event_date DATE NOT NULL,
    event_time TIME,
    duration_minutes INTEGER DEFAULT 120,
    category TEXT CHECK(category IN ('work-day', 'meeting', 'social', 'workshop', 'maintenance')),
    location TEXT,
    max_attendees INTEGER,
    current_attendees INTEGER DEFAULT 0,
    is_recurring BOOLEAN DEFAULT FALSE,
    recurrence_pattern TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### Applicants Table
```sql
CREATE TABLE applicants (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL,
    phone TEXT,
    address TEXT,
    age INTEGER,
    occupation TEXT,
    skills TEXT,
    experience_level TEXT CHECK(experience_level IN ('beginner', 'intermediate', 'advanced')),
    availability TEXT,
    motivation TEXT,
    message TEXT,
    status TEXT DEFAULT 'pending' CHECK(status IN ('pending', 'approved', 'rejected', 'waitlist')),
    reviewed_by INTEGER,
    reviewed_at TIMESTAMP,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reviewed_by) REFERENCES users(id)
);
```

#### Expenses Table
```sql
CREATE TABLE expenses (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    description TEXT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    category TEXT CHECK(category IN ('tools', 'materials', 'utilities', 'maintenance', 'events', 'admin', 'other')),
    date DATE NOT NULL,
    vendor TEXT,
    payment_method TEXT,
    receipt_url TEXT,
    notes TEXT,
    approved_by INTEGER,
    approved_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (approved_by) REFERENCES users(id)
);
```

#### Users Table
```sql
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE NOT NULL,
    email TEXT UNIQUE NOT NULL,
    password_hash TEXT NOT NULL,
    first_name TEXT,
    last_name TEXT,
    role TEXT DEFAULT 'admin' CHECK(role IN ('admin', 'moderator', 'viewer')),
    is_active BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### Tasks Table
```sql
CREATE TABLE tasks (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    description TEXT,
    project_id INTEGER,
    category TEXT CHECK(category IN ('water-systems', 'natural-building', 'gardening', 'land-maintenance', 'housekeeping', 'community', 'education', 'sustainability')),
    status TEXT DEFAULT 'open' CHECK(status IN ('open', 'in-progress', 'completed', 'cancelled')),
    priority TEXT DEFAULT 'normal' CHECK(priority IN ('low', 'normal', 'high', 'urgent')),
    assigned_to INTEGER,
    estimated_hours DECIMAL(3,1),
    actual_hours DECIMAL(3,1),
    due_date DATE,
    location TEXT,
    recurring_pattern TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE SET NULL,
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL
);
```

#### Project Participants Table
```sql
CREATE TABLE project_participants (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    project_id INTEGER NOT NULL,
    applicant_id INTEGER NOT NULL,
    status TEXT DEFAULT 'registered' CHECK(status IN ('registered', 'confirmed', 'attended', 'no-show')),
    hours_contributed DECIMAL(5,2) DEFAULT 0,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    FOREIGN KEY (applicant_id) REFERENCES applicants(id) ON DELETE CASCADE
);
```

#### Event Attendees Table
```sql
CREATE TABLE event_attendees (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    event_id INTEGER NOT NULL,
    applicant_id INTEGER NOT NULL,
    status TEXT DEFAULT 'registered' CHECK(status IN ('registered', 'confirmed', 'attended', 'no-show')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    FOREIGN KEY (applicant_id) REFERENCES applicants(id) ON DELETE CASCADE
);
```

#### Activity Logs Table
```sql
CREATE TABLE activity_logs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    applicant_id INTEGER NOT NULL,
    task_id INTEGER,
    project_id INTEGER,
    category TEXT CHECK(category IN ('water-systems', 'natural-building', 'gardening', 'land-maintenance', 'housekeeping', 'community', 'education', 'sustainability')),
    title TEXT NOT NULL,
    description TEXT,
    start_time TIME,
    end_time TIME,
    hours_worked DECIMAL(4,2),
    mood TEXT CHECK(mood IN ('great', 'good', 'okay', 'difficult')),
    location TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (applicant_id) REFERENCES applicants(id),
    FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE SET NULL,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE SET NULL
);
```

#### Agreements Table
```sql
CREATE TABLE agreements (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    applicant_id INTEGER NOT NULL,
    agreement_type TEXT CHECK(agreement_type IN ('wwoof', 'volunteer', 'internship', 'other')),
    agreement_name TEXT NOT NULL,
    valid_from DATE,
    valid_to DATE,
    file_url TEXT,
    file_name TEXT,
    file_size INTEGER,
    status TEXT DEFAULT 'active' CHECK(status IN ('active', 'expired', 'terminated')),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (applicant_id) REFERENCES applicants(id)
);
```

#### Suggestions Table
```sql
CREATE TABLE suggestions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    applicant_id INTEGER NOT NULL,
    category TEXT CHECK(category IN ('water-systems', 'natural-building', 'gardening', 'land-maintenance', 'housekeeping', 'community', 'education', 'sustainability')),
    title TEXT NOT NULL,
    description TEXT,
    benefits TEXT,
    implementation_notes TEXT,
    priority TEXT DEFAULT 'normal' CHECK(priority IN ('low', 'normal', 'high', 'urgent')),
    status TEXT DEFAULT 'pending' CHECK(status IN ('pending', 'approved', 'implemented', 'rejected')),
    reviewed_by INTEGER,
    reviewed_at TIMESTAMP,
    review_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (applicant_id) REFERENCES applicants(id),
    FOREIGN KEY (reviewed_by) REFERENCES users(id)
);
```

#### Project Proposals Table
```sql
CREATE TABLE project_proposals (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    applicant_id INTEGER NOT NULL,
    title TEXT NOT NULL,
    description TEXT,
    category TEXT CHECK(category IN ('water-systems', 'natural-building', 'gardening', 'land-maintenance', 'housekeeping', 'community', 'education', 'sustainability')),
    importance TEXT,
    requirements TEXT,
    duration TEXT,
    leadership_preference TEXT CHECK(leadership_preference IN ('yes', 'maybe', 'no')),
    estimated_hours DECIMAL(5,2),
    location TEXT,
    status TEXT DEFAULT 'pending' CHECK(status IN ('pending', 'approved', 'rejected', 'converted')),
    reviewed_by INTEGER,
    reviewed_at TIMESTAMP,
    review_notes TEXT,
    converted_to_project_id INTEGER,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (applicant_id) REFERENCES applicants(id),
    FOREIGN KEY (reviewed_by) REFERENCES users(id),
    FOREIGN KEY (converted_to_project_id) REFERENCES projects(id) ON DELETE SET NULL
);
```

#### Learning Resources Table
```sql
CREATE TABLE learning_resources (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    description TEXT,
    category TEXT CHECK(category IN ('guides', 'contacts', 'maps', 'notion', 'other')),
    resource_type TEXT CHECK(resource_type IN ('document', 'link', 'contact', 'map', 'notion-page')),
    url TEXT,
    file_url TEXT,
    file_name TEXT,
    file_size INTEGER,
    is_active BOOLEAN DEFAULT TRUE,
    created_by INTEGER,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
);
```

#### Enhanced Projects Table Fields
```sql
-- Add these fields to the existing projects table
ALTER TABLE projects ADD COLUMN importance TEXT;
ALTER TABLE projects ADD COLUMN requirements TEXT;
ALTER TABLE projects ADD COLUMN duration TEXT;
ALTER TABLE projects ADD COLUMN leadership_preference TEXT;
ALTER TABLE projects ADD COLUMN proposed_by INTEGER;
ALTER TABLE projects ADD COLUMN proposal_status TEXT DEFAULT 'pending' CHECK(proposal_status IN ('pending', 'approved', 'rejected'));
ALTER TABLE projects ADD COLUMN review_notes TEXT;
ALTER TABLE projects ADD COLUMN reviewed_by INTEGER;
ALTER TABLE projects ADD COLUMN reviewed_at TIMESTAMP;
ALTER TABLE projects ADD FOREIGN KEY (proposed_by) REFERENCES applicants(id);
ALTER TABLE projects ADD FOREIGN KEY (reviewed_by) REFERENCES users(id);
```

#### Enhanced Tasks Table Fields
```sql
-- Add these fields to the existing tasks table
ALTER TABLE tasks ADD COLUMN mood TEXT CHECK(mood IN ('great', 'good', 'okay', 'difficult'));
ALTER TABLE tasks ADD COLUMN start_time TIME;
ALTER TABLE tasks ADD COLUMN end_time TIME;
ALTER TABLE tasks ADD COLUMN location TEXT;
ALTER TABLE tasks ADD COLUMN notes TEXT;
```

### Indexes for Performance
```sql
-- Projects indexes
CREATE INDEX idx_projects_status ON projects(status);
CREATE INDEX idx_projects_category ON projects(category);
CREATE INDEX idx_projects_date ON projects(start_date, end_date);

-- Events indexes
CREATE INDEX idx_events_date ON events(event_date);
CREATE INDEX idx_events_category ON events(category);

-- Applicants indexes
CREATE INDEX idx_applicants_status ON applicants(status);
CREATE INDEX idx_applicants_email ON applicants(email);

-- Expenses indexes
CREATE INDEX idx_expenses_date ON expenses(date);
CREATE INDEX idx_expenses_category ON expenses(category);

-- Tasks indexes
CREATE INDEX idx_tasks_project ON tasks(project_id);
CREATE INDEX idx_tasks_assigned ON tasks(assigned_to);
CREATE INDEX idx_tasks_status ON tasks(status);
CREATE INDEX idx_tasks_priority ON tasks(priority);
CREATE INDEX idx_tasks_category ON tasks(category);
CREATE INDEX idx_tasks_due_date ON tasks(due_date);

-- Participants indexes
CREATE INDEX idx_project_participants_project ON project_participants(project_id);
CREATE INDEX idx_event_attendees_event ON event_attendees(event_id);

-- Activity Logs indexes
CREATE INDEX idx_activity_logs_applicant ON activity_logs(applicant_id);
CREATE INDEX idx_activity_logs_task ON activity_logs(task_id);
CREATE INDEX idx_activity_logs_project ON activity_logs(project_id);
CREATE INDEX idx_activity_logs_category ON activity_logs(category);
CREATE INDEX idx_activity_logs_date ON activity_logs(created_at);

-- Agreements indexes
CREATE INDEX idx_agreements_applicant ON agreements(applicant_id);
CREATE INDEX idx_agreements_status ON agreements(status);
CREATE INDEX idx_agreements_type ON agreements(agreement_type);
CREATE INDEX idx_agreements_validity ON agreements(valid_from, valid_to);

-- Suggestions indexes
CREATE INDEX idx_suggestions_applicant ON suggestions(applicant_id);
CREATE INDEX idx_suggestions_category ON suggestions(category);
CREATE INDEX idx_suggestions_status ON suggestions(status);
CREATE INDEX idx_suggestions_priority ON suggestions(priority);
CREATE INDEX idx_suggestions_reviewed_by ON suggestions(reviewed_by);

-- Project Proposals indexes
CREATE INDEX idx_project_proposals_applicant ON project_proposals(applicant_id);
CREATE INDEX idx_project_proposals_category ON project_proposals(category);
CREATE INDEX idx_project_proposals_status ON project_proposals(status);
CREATE INDEX idx_project_proposals_reviewed_by ON project_proposals(reviewed_by);

-- Learning Resources indexes
CREATE INDEX idx_learning_resources_category ON learning_resources(category);
CREATE INDEX idx_learning_resources_type ON learning_resources(resource_type);
CREATE INDEX idx_learning_resources_active ON learning_resources(is_active);
CREATE INDEX idx_learning_resources_created_by ON learning_resources(created_by);

-- Enhanced Projects indexes
CREATE INDEX idx_projects_proposed_by ON projects(proposed_by);
CREATE INDEX idx_projects_proposal_status ON projects(proposal_status);
CREATE INDEX idx_projects_reviewed_by ON projects(reviewed_by);

-- Enhanced Tasks indexes
CREATE INDEX idx_tasks_mood ON tasks(mood);
CREATE INDEX idx_tasks_location ON tasks(location);
```

---

## Dashboard Database Integration

### WWOOFer Dashboard Database Support

#### **Celebrate Your Impact (Activity Logging)**
- **Primary Table**: `activity_logs`
- **Supporting Tables**: `tasks`, `projects`, `applicants`
- **Key Features**:
  - Log work hours with mood tracking
  - Link activities to specific tasks or projects
  - Category-based organization
  - Time tracking with start/end times

#### **Join a Mission (Project Participation)**
- **Primary Table**: `projects`
- **Supporting Tables**: `project_participants`, `applicants`
- **Key Features**:
  - View available missions
  - Join missions with status tracking
  - Track participation hours

#### **Dream Up a Mission (Project Proposals)**
- **Primary Table**: `project_proposals`
- **Supporting Tables**: `applicants`, `projects` (when converted)
- **Key Features**:
  - Submit mission proposals
  - Leadership preference tracking
  - Review and approval workflow

#### **My Adventure Pact (Agreements)**
- **Primary Table**: `agreements`
- **Supporting Tables**: `applicants`
- **Key Features**:
  - Upload and manage PDF agreements
  - Track agreement validity periods
  - Status management (active/expired/terminated)

#### **Spark Ideas (Suggestions)**
- **Primary Table**: `suggestions`
- **Supporting Tables**: `applicants`, `users` (reviewers)
- **Key Features**:
  - Submit community improvement ideas
  - Review and approval workflow
  - Priority and status tracking

#### **Grow & Explore (Learning Resources)**
- **Primary Table**: `learning_resources`
- **Supporting Tables**: `users` (creators)
- **Key Features**:
  - Access guides, contacts, maps
  - Notion hub integration
  - Resource categorization

### Stewart Dashboard Database Support

#### **Active Missions (Project Management)**
- **Primary Tables**: `projects`, `tasks`
- **Supporting Tables**: `project_participants`, `applicants`
- **Key Features**:
  - View and manage all projects
  - Assign tasks to WWOOFers
  - Track project progress and completion

#### **Dream Projects (Proposal Review)**
- **Primary Tables**: `project_proposals`, `suggestions`
- **Supporting Tables**: `applicants`, `users`
- **Key Features**:
  - Review WWOOFer proposals
  - Approve/reject suggestions
  - Convert proposals to projects

#### **Resource Flow (Expense Tracking)**
- **Primary Table**: `expenses`
- **Supporting Tables**: `users` (approvers)
- **Key Features**:
  - Track project expenses
  - Categorize spending
  - Approval workflow

#### **Team Coordination**
- **Primary Tables**: `applicants`, `project_participants`
- **Supporting Tables**: `activity_logs`, `tasks`
- **Key Features**:
  - View WWOOFer profiles and skills
  - Track participation and contributions
  - Monitor work hours and mood

### Admin Dashboard Database Support

#### **System Overview**
- **Primary Tables**: All tables for comprehensive view
- **Key Features**:
  - Financial overview (expenses)
  - User management (applicants, users)
  - Project analytics (projects, tasks, activity_logs)

#### **Financial Management**
- **Primary Table**: `expenses`
- **Supporting Tables**: `users`, `projects`
- **Key Features**:
  - Expense tracking and categorization
  - Budget management
  - Financial reporting

#### **User Management**
- **Primary Tables**: `applicants`, `users`
- **Supporting Tables**: `agreements`, `activity_logs`
- **Key Features**:
  - WWOOFer application review
  - Agreement management
  - Performance tracking

#### **Content Management**
- **Primary Table**: `learning_resources`
- **Supporting Tables**: `users`
- **Key Features**:
  - Manage learning resources
  - Update guides and contacts
  - Content approval workflow

### Cross-Dashboard Data Flow

#### **Project Lifecycle**
1. **WWOOFer**: Submits proposal via `project_proposals`
2. **Stewart**: Reviews and converts to `projects`
3. **Admin**: Monitors progress and approves expenses
4. **WWOOFer**: Joins project via `project_participants`
5. **WWOOFer**: Logs activities via `activity_logs`
6. **Stewart**: Tracks progress and assigns tasks
7. **Admin**: Reviews completion and financial impact

#### **Suggestion Workflow**
1. **WWOOFer**: Submits suggestion via `suggestions`
2. **Stewart**: Reviews and approves/rejects
3. **Admin**: Monitors community feedback and implementation

#### **Agreement Management**
1. **WWOOFer**: Uploads agreement via `agreements`
2. **Admin**: Reviews and manages validity
3. **Stewart**: References for project assignments

#### **Activity Tracking**
1. **WWOOFer**: Logs activities via `activity_logs`
2. **Stewart**: Monitors team performance and mood
3. **Admin**: Analyzes overall community engagement

### Database Relationships Summary

```
applicants (WWOOFers)
├── activity_logs (work tracking)
├── agreements (contracts)
├── suggestions (ideas)
├── project_proposals (missions)
└── project_participants (participation)

projects (missions)
├── tasks (work items)
├── project_participants (team)
├── expenses (costs)
└── activity_logs (contributions)

users (admins/stewards)
├── expenses (approvals)
├── suggestions (reviews)
├── project_proposals (reviews)
└── learning_resources (content)
```

---

## Design System

All dashboards (Admin, Stewart, WWOOFer) now use a unified CSS for grid layouts, dashboard cards, and link styles. The .dashboard-grid and .dashboard-square classes are shared, ensuring a consistent, modern, and mobile-first design across all roles. The Admin dashboard header no longer has upper right action buttons, and the Recent Activities section uses the same card/activity-item design as the Stewart dashboard. All visual and UX elements are now consistent across dashboards.

### Color Palette
```css
:root {
  /* Primary Colors */
  --primary-green: #2d5a27;
  --primary-green-light: #4a7c59;
  --primary-green-dark: #1a3d1a;
  
  /* Secondary Colors */
  --secondary-earth: #8b7355;
  --secondary-earth-light: #a89078;
  --secondary-earth-dark: #6b5b47;
  
  /* Neutral Colors */
  --neutral-white: #ffffff;
  --neutral-light: #f8f9fa;
  --neutral-gray: #6c757d;
  --neutral-dark: #343a40;
  
  /* Accent Colors */
  --accent-warm: #d4a574;
  --accent-cool: #87ceeb;
  --accent-warning: #ffc107;
  --accent-success: #28a745;
  --accent-danger: #dc3545;
  
  /* Semantic Colors */
  --text-primary: var(--neutral-dark);
  --text-secondary: var(--neutral-gray);
  --background-primary: var(--neutral-white);
  --background-secondary: var(--neutral-light);
  --border-color: #dee2e6;
}
```

### Typography
```css
:root {
  /* Font Families */
  --font-primary: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
  --font-secondary: 'Georgia', serif;
  
  /* Font Sizes */
  --text-xs: 0.75rem;    /* 12px */
  --text-sm: 0.875rem;   /* 14px */
  --text-base: 1rem;     /* 16px */
  --text-lg: 1.125rem;   /* 18px */
  --text-xl: 1.25rem;    /* 20px */
  --text-2xl: 1.5rem;    /* 24px */
  --text-3xl: 1.875rem;  /* 30px */
  --text-4xl: 2.25rem;   /* 36px */
  
  /* Line Heights */
  --leading-tight: 1.25;
  --leading-normal: 1.5;
  --leading-relaxed: 1.75;
}
```

### Spacing System
```css
:root {
  /* Spacing Scale */
  --space-1: 0.25rem;   /* 4px */
  --space-2: 0.5rem;    /* 8px */
  --space-3: 0.75rem;   /* 12px */
  --space-4: 1rem;      /* 16px */
  --space-5: 1.25rem;   /* 20px */
  --space-6: 1.5rem;    /* 24px */
  --space-8: 2rem;      /* 32px */
  --space-10: 2.5rem;   /* 40px */
  --space-12: 3rem;     /* 48px */
  --space-16: 4rem;     /* 64px */
  --space-20: 5rem;     /* 80px */
}
```

### Layout System

#### CSS Grid Layout
```css
/* Main Layout Grid */
.main-grid {
  display: grid;
  grid-template-columns: 1fr;
  grid-template-rows: auto 1fr auto;
  min-height: 100vh;
}

/* Content Grid */
.content-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: var(--space-6);
  padding: var(--space-6);
}

/* Admin Dashboard Grid */
.admin-grid {
  display: grid;
  grid-template-columns: 250px 1fr;
  grid-template-rows: auto 1fr;
  min-height: 100vh;
}

/* Responsive Breakpoints */
@media (min-width: 768px) {
  .content-grid {
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
  }
}

@media (min-width: 1024px) {
  .content-grid {
    grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
  }
}
```

#### Flexbox Components
```css
/* Navigation */
.nav-flex {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: var(--space-4) var(--space-6);
}

/* Card Layout */
.card-flex {
  display: flex;
  flex-direction: column;
  gap: var(--space-4);
  padding: var(--space-6);
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Form Layout */
.form-flex {
  display: flex;
  flex-direction: column;
  gap: var(--space-4);
  max-width: 600px;
  margin: 0 auto;
}
```

### Component Library

#### Buttons
```css
.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: var(--space-3) var(--space-6);
  border-radius: 6px;
  font-weight: 500;
  text-decoration: none;
  transition: all 0.2s ease;
  cursor: pointer;
  border: none;
}

.btn-primary {
  background-color: var(--primary-green);
  color: var(--neutral-white);
}

.btn-secondary {
  background-color: var(--secondary-earth);
  color: var(--neutral-white);
}

.btn-outline {
  background-color: transparent;
  border: 2px solid var(--primary-green);
  color: var(--primary-green);
}
```

#### Cards
```css
.card {
  background: var(--background-primary);
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  transition: transform 0.2s ease;
}

.card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}
```

#### Forms
```css
.form-group {
  display: flex;
  flex-direction: column;
  gap: var(--space-2);
}

.form-input {
  padding: var(--space-3);
  border: 1px solid var(--border-color);
  border-radius: 4px;
  font-size: var(--text-base);
  transition: border-color 0.2s ease;
}

.form-input:focus {
  outline: none;
  border-color: var(--primary-green);
  box-shadow: 0 0 0 3px rgba(45, 90, 39, 0.1);
}
```

---

## Performance Guidelines

### Loading Strategy
1. **Critical CSS Inline**: First paint styles in `<head>`
2. **Lazy Loading**: Images and non-critical content
3. **Minimal Dependencies**: Vanilla JS, no heavy frameworks
4. **Optimized Assets**: Compressed images, minified CSS/JS

### Image Optimization
```html
<!-- Responsive Images -->
<img src="image-300w.jpg" 
     srcset="image-300w.jpg 300w, image-600w.jpg 600w, image-900w.jpg 900w"
     sizes="(max-width: 600px) 300px, (max-width: 900px) 600px, 900px"
     loading="lazy"
     alt="Description">
```

### CSS Optimization
```css
/* Critical CSS (inline in head) */
.critical-styles {
  /* Above-the-fold styles only */
}

/* Non-critical CSS (loaded asynchronously) */
@import url('non-critical.css') print;
```

### JavaScript Strategy
```javascript
// Lazy load components
const loadComponent = async (componentName) => {
  const module = await import(`./components/${componentName}.js`);
  return module.default;
};

// Intersection Observer for lazy loading
const observerOptions = {
  rootMargin: '50px',
  threshold: 0.1
};

const imageObserver = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      const img = entry.target;
      img.src = img.dataset.src;
      img.classList.remove('lazy');
      imageObserver.unobserve(img);
    }
  });
}, observerOptions);
```

### Database Performance
1. **Indexed Queries**: All search and filter operations
2. **Connection Pooling**: Reuse database connections
3. **Query Optimization**: Limit results, use pagination
4. **Caching**: Cache frequently accessed data

### Caching Strategy
```javascript
// Simple in-memory cache
const cache = new Map();

const getCachedData = async (key, fetchFunction) => {
  if (cache.has(key)) {
    return cache.get(key);
  }
  
  const data = await fetchFunction();
  cache.set(key, data);
  return data;
};
```

---

## Future Features

### Phase 2 Enhancements
- [ ] **Member Portal**: Individual member dashboards
- [ ] **Project Templates**: Reusable project structures
- [ ] **Automated Notifications**: Email/SMS reminders
- [ ] **Photo Gallery**: Project documentation
- [ ] **Resource Library**: Documents, guides, tutorials

### Phase 3 Advanced Features
- [ ] **Mobile App**: Native iOS/Android companion
- [ ] **Real-time Chat**: Member communication
- [ ] **Skill Matching**: Connect members by expertise
- [ ] **Progress Tracking**: Project completion metrics
- [ ] **Financial Reports**: Automated expense analysis

### Phase 4 Integration Features
- [ ] **Weather Integration**: Work day planning
- [ ] **Calendar Sync**: Google Calendar integration
- [ ] **Social Media**: Auto-post project updates
- [ ] **Maps Integration**: Location-based features
- [ ] **API Development**: Third-party integrations

### Technical Improvements
- [ ] **PWA Implementation**: Offline functionality
- [ ] **Service Workers**: Background sync
- [ ] **Advanced Caching**: Redis implementation
- [ ] **CDN Integration**: Global content delivery
- [ ] **Analytics Dashboard**: Usage insights

### Community Features
- [ ] **Discussion Forums**: Topic-based conversations
- [ ] **Voting System**: Community decision making
- [ ] **Achievement System**: Recognition badges
- [ ] **Mentorship Program**: Skill sharing
- [ ] **Event Planning**: Advanced calendar features

---

## Implementation Checklist

### Foundation (Week 1)
- [ ] Set up project structure
- [ ] Create database schema
- [ ] Implement basic HTML templates
- [ ] Set up CSS Grid/Flexbox system
- [ ] Create responsive navigation

### Public Pages (Week 2)
- [ ] Home page with hero section
- [ ] About page with content sections
- [ ] Projects page with filtering
- [ ] Calendar with event display
- [ ] Contact and application forms

### Admin System (Week 3)
- [ ] Admin authentication
- [ ] Project management interface
- [ ] Applicant tracking system
- [ ] Expense tracking
- [ ] Content management

### Optimization (Week 4)
- [ ] Performance optimization
- [ ] SEO implementation
- [ ] Mobile testing
- [ ] Content population
- [ ] Final testing and deployment

---

*Last Updated: [Current Date]*
*Version: 1.0*
*Status: Planning Phase* 