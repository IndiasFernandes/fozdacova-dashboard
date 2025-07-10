# Foz da Cova Dashboard System - Complete Overview

## System Architecture

The Foz da Cova dashboard system consists of **three distinct user interfaces**, each designed for specific roles and responsibilities within the community land project.

---

## 1. Admin Dashboard
**File:** `DASHBOARD_DESIGN_6_HYBRID.html`

### Role & Responsibilities
- **Full system oversight** and management
- **Financial control** and expense tracking
- **User management** and permissions
- **Strategic decision making**
- **System configuration** and settings

### Key Features
- **Quick Actions Grid** - Add projects, residents, calls, expenses, logs, reports
- **Financial Overview** - Monthly expenses tracking
- **Action Logs** - Complete activity history
- **Current Residents** - Who is at Foz da Cova with contact info
- **Proposed Projects** - New project suggestions from community
- **Next Calls** - Monday 5 PM Portugal time scheduling

### Data Management
- View all projects (active, proposed, completed)
- Manage all residents and their contact information
- Track all expenses and financial reports
- Monitor all action logs and activities
- Oversee call scheduling and participation

---

## 2. Stewart Dashboard
**File:** `STEWART_DASHBOARD.html`

### Role & Responsibilities
- **Project coordination** and management
- **Team leadership** and task assignment
- **Progress tracking** and reporting
- **Resource coordination**
- **Communication** with WWOOFers and other stewards

### Key Features
- **Project Management** - Active projects I'm coordinating
- **Team Coordination** - Managing team members and assignments
- **Schedule Management** - Weekly activity planning
- **Resource Tracking** - Tools and materials needed
- **Progress Updates** - Logging project milestones
- **Communication Tools** - Team coordination features

### Data Management
- Manage assigned projects and their progress
- Coordinate team members and their tasks
- Track project resources and materials
- Log coordination activities and decisions
- Report progress to admin level

### Stewart Permissions
- ✅ Create and manage project tasks
- ✅ Assign team members to projects
- ✅ Log project progress and activities
- ✅ Coordinate with other stewards
- ✅ Request resources and materials
- ❌ Cannot manage finances
- ❌ Cannot change system settings
- ❌ Cannot manage user permissions

---

## 3. WWOOFer Dashboard
**File:** `WWOOFER_DASHBOARD.html`

### Role & Responsibilities
- **Project participation** and contribution
- **Skill learning** and development
- **Activity logging** and feedback
- **Community engagement**
- **Schedule adherence**

### Key Features
- **Available Projects** - Join projects that need participants
- **My Activities** - Track personal contributions
- **Learning Resources** - Skill development materials
- **Participation Stats** - Hours worked, projects joined
- **Community Schedule** - Upcoming events and calls
- **Skill Tracking** - New skills learned

### Data Management
- Join available projects
- Log personal activities and hours
- Track learning progress
- View community schedule
- Participate in calls and events

### WWOOFer Permissions
- ✅ Join available projects
- ✅ Log personal activities and hours
- ✅ View learning resources
- ✅ Participate in community calls
- ✅ View project details and requirements
- ❌ Cannot create or manage projects
- ❌ Cannot assign tasks to others
- ❌ Cannot access financial data
- ❌ Cannot manage system settings

---

## User Hierarchy & Permissions

### Permission Levels
```
Admin (Full Access)
├── Financial Management
├── User Management
├── System Configuration
├── All Data Access
└── Strategic Oversight

Stewart (Project Management)
├── Project Coordination
├── Team Management
├── Progress Tracking
├── Resource Coordination
└── Communication Tools

WWOOFer (Participation)
├── Project Participation
├── Activity Logging
├── Learning Resources
├── Community Engagement
└── Schedule Viewing
```

### Data Access Matrix
| Feature | Admin | Stewart | WWOOFer |
|---------|-------|---------|---------|
| All Projects | ✅ | ✅ (Assigned) | ✅ (Available) |
| All Residents | ✅ | ✅ (Team) | ❌ |
| Financial Data | ✅ | ❌ | ❌ |
| Action Logs | ✅ | ✅ (Own) | ✅ (Own) |
| System Settings | ✅ | ❌ | ❌ |
| User Management | ✅ | ❌ | ❌ |

---

## Key System Features

### 1. **Project Management Flow**
```
Admin creates project
    ↓
Stewart coordinates project
    ↓
WWOOFers join and participate
    ↓
Activities are logged
    ↓
Progress is tracked
    ↓
Admin reviews and approves
```

### 2. **Activity Logging System**
- **Admin:** View all logs across all users
- **Stewart:** Log coordination activities and project progress
- **WWOOFer:** Log personal contributions and learning

### 3. **Communication System**
- **Monday Calls:** 5 PM Portugal time
- **Project Updates:** Real-time progress tracking
- **Resource Requests:** Stewart to Admin communication
- **Community Engagement:** WWOOFer participation

### 4. **Financial Management**
- **Admin Only:** Full financial control
- **Expense Tracking:** Monthly budgets and reports
- **Resource Allocation:** Project funding and materials
- **Financial Reports:** Transparency and accountability

---

## Database Schema Integration

### Core Tables (All Users)
```sql
-- Projects (Admin/Stewart can manage, WWOOFer can view)
-- Residents (Admin/Stewart can manage, WWOOFer can view own)
-- Activities (All users can log, Admin can view all)
-- Expenses (Admin only)
-- Calls (All users can view, Admin/Stewart can manage)
-- Learning Resources (All users can access)
```

### User-Specific Views
- **Admin:** Full access to all tables
- **Stewart:** Filtered views based on assigned projects
- **WWOOFer:** Limited views for participation only

---

## Implementation Priority

### Phase 1: Core System
1. **Admin Dashboard** - Full functionality
2. **Database Integration** - SQLite implementation
3. **User Authentication** - Role-based access

### Phase 2: Stewart Features
1. **Project Management** - Coordination tools
2. **Team Management** - Assignment features
3. **Progress Tracking** - Milestone logging

### Phase 3: WWOOFer Features
1. **Project Participation** - Join/leave projects
2. **Activity Logging** - Personal contribution tracking
3. **Learning Resources** - Skill development

### Phase 4: Advanced Features
1. **Communication Tools** - Real-time messaging
2. **Reporting System** - Analytics and insights
3. **Mobile Optimization** - Field work support

---

## Design Consistency

### Visual Identity
- **Color System:** Consistent across all dashboards
- **Typography:** Inter font family throughout
- **Layout Patterns:** Responsive design principles
- **Interaction Patterns:** Familiar navigation and actions

### User Experience
- **Role-Based Interface:** Each dashboard optimized for specific role
- **Progressive Disclosure:** Information shown based on permissions
- **Mobile-First:** All dashboards work on mobile devices
- **Accessibility:** Screen reader friendly and keyboard navigable

---

## Technical Implementation

### File Structure
```
dashboards/
├── admin/
│   ├── index.html
│   ├── projects.html
│   ├── residents.html
│   ├── expenses.html
│   └── settings.html
├── stewart/
│   ├── index.html
│   ├── projects.html
│   ├── team.html
│   └── coordination.html
├── wwoofer/
│   ├── index.html
│   ├── projects.html
│   ├── activities.html
│   └── learning.html
└── shared/
    ├── css/
    ├── js/
    └── components/
```

### Database Integration
- **SQLite Database:** Lightweight and portable
- **Role-Based Queries:** Filtered data access
- **Real-Time Updates:** Activity logging and progress tracking
- **Data Export:** Reports and analytics

---

*This system provides a complete solution for managing the Foz da Cova community land project with appropriate role-based access and functionality for each user type.* 