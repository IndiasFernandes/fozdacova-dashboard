# Foz da Cova Dashboard

A comprehensive community land project management system with role-based access control for Admin, Stewart, and WWOOFer roles.

## 🌱 Overview

Foz da Cova Dashboard is a lightweight, PHP-based web application designed for community land project management. It provides three distinct user interfaces:

- **Admin Dashboard**: Full system oversight, financial control, and strategic management
- **Stewart Dashboard**: Project coordination, team management, and resource flow
- **WWOOFer Dashboard**: Activity logging, project participation, and learning resources

## 🚀 Quick Start

### Production Deployment

1. **Upload to cPanel**
   - Upload `public_html/` contents to your `public_html/` directory
   - Upload `sketch/` to your root directory (outside web root)

2. **Set Permissions**
   ```bash
   chmod 755 public_html/
   chmod 644 public_html/*.php
   chmod 755 data/
   chmod 644 data/foz_da_cova.db
   ```

3. **Database Setup**
   - Access `yourdomain.com/admin/setup.php` to create database
   - Or run via SSH: `php admin/setup.php`

4. **Access the Application**
   - **Main App**: `yourdomain.com/`
   - **Sketch Pages**: `yourdomain.com/sketch/`

### Local Development

1. **Install PHP**
   ```bash
   # macOS
   brew install php
   
   # Ubuntu/Debian
   sudo apt-get install php php-sqlite3
   ```

2. **Start Development Server**
   ```bash
   cd public_html/
   php -S localhost:8000
   ```

3. **Access Application**
   - **Main App**: `http://localhost:8000/`
   - **Sketch Pages**: `http://localhost:8000/sketch/`

## 🏗️ System Architecture

### Technology Stack
- **Backend**: PHP 7.4+ with SQLite database
- **Frontend**: Vanilla HTML/CSS/JavaScript
- **Authentication**: Session-based with role-based access control
- **Database**: SQLite (lightweight, file-based)
- **Deployment**: cPanel compatible

### File Structure
```
foz-da-cova/
├── public_html/            # Production web root
│   ├── index.php          # Main router
│   ├── login.php          # Authentication
│   ├── logout.php         # Logout handler
│   ├── demo.html          # Static demo version
│   ├── assets/            # CSS, JS, images
│   ├── admin/             # Admin pages
│   ├── stewart/           # Stewart pages
│   ├── wwoofer/           # WWOOFer pages
│   ├── includes/          # PHP backend
│   ├── data/              # SQLite database
│   └── .htaccess          # URL routing
├── sketch/                 # Original HTML prototypes
│   ├── dashboards/        # Original dashboard designs
│   ├── public/            # Original public pages
│   ├── docs/              # Documentation
│   └── .htaccess          # Sketch routing
└── docs/                  # Additional documentation
```

## 🎯 Features

### Admin Dashboard
- **System Overview**: Quick statistics and recent activities
- **Project Management**: Create, edit, and track all projects
- **Expense Tracking**: Monitor financial flow and approve expenses
- **User Management**: Manage all system users
- **Suggestion Review**: Review and approve community suggestions
- **Knowledge Base**: Manage learning resources

### Stewart Dashboard
- **Active Missions**: Coordinate ongoing projects
- **Resource Flow**: Track expenses and resource requests
- **Dream Projects**: Review WWOOFer suggestions
- **Team Coordination**: Manage project participants
- **Learning Resources**: Access guides and documentation

### WWOOFer Dashboard
- **Join Missions**: Browse and join available projects
- **Celebrate Impact**: Log activities and track contributions
- **Spark Ideas**: Submit suggestions and ideas
- **Grow & Explore**: Access learning resources
- **Personal Progress**: Track hours and mood

## 🗄️ Database Schema

### Core Tables
- **users**: User accounts with role-based access
- **projects**: Mission/project definitions
- **suggestions**: Community ideas and suggestions
- **expenses**: Financial tracking and resource flow
- **activities**: WWOOFer work logging
- **project_participants**: Project participation tracking
- **learning_resources**: Educational content and guides

## 🔐 Demo Credentials

| Role | Username | Password |
|------|----------|----------|
| Admin | admin | admin123 |
| Stewart | maria | maria123 |
| WWOOFer | joao | joao123 |
| WWOOFer | ana | ana123 |

## 🎨 Design System

### Color Palette
- **Primary Green**: #2d5a27 (buttons, accents)
- **Earth Brown**: #8b7355 (backgrounds, secondary UI)
- **Sky Blue**: #87ceeb (highlights, calendar)
- **Neutral Grays**: Clean, minimal interface

### Typography
- **Font**: Inter (clean, modern sans-serif)
- **Hierarchy**: Clear heading and text sizes
- **Readability**: Optimized for mobile and desktop

### Layout
- **Mobile-first**: Responsive design for all devices
- **CSS Grid**: Modern layout system
- **Card-based**: Clean, organized interface
- **Consistent spacing**: Unified design system

## 🔒 Security Features

- **Session-based authentication**
- **Role-based access control**
- **SQL injection prevention**
- **XSS protection**
- **Input validation**
- **Secure file uploads**

## 📱 Mobile Support

- **Responsive design** for all screen sizes
- **Touch-friendly** interactions
- **Mobile-optimized** navigation
- **Fast loading** on mobile networks

## 🚀 Deployment

### cPanel Deployment
1. Upload `public_html/` contents to `public_html/`
2. Upload `sketch/` to root directory (outside web root)
3. Set proper file permissions
4. Run database setup script
5. Configure `.htaccess` for clean URLs

### Local Development
1. Install PHP and SQLite
2. Run `php -S localhost:8000 -t public_html/`
3. Access at `http://localhost:8000`

## 🔧 Configuration

### Environment Variables
- Database path: `DB_PATH` in `includes/config.php`
- App settings: `APP_NAME`, `APP_VERSION`
- Session settings: Configure in `includes/config.php`

### Customization
- **Colors**: Modify CSS variables in `assets/css/main.css`
- **Content**: Edit template files in `includes/templates/`
- **Database**: Add tables or modify schema in `admin/setup.php`

## 📊 Performance

### Optimization Features
- **Minimal dependencies**: No heavy frameworks
- **Optimized queries**: Efficient database operations
- **Caching**: Browser caching for static assets
- **Lazy loading**: Progressive enhancement
- **Compressed assets**: Minified CSS/JS

### Performance Targets
- **Page load**: Under 2 seconds
- **Database queries**: Optimized with indexes
- **Mobile performance**: Touch-friendly and fast
- **Scalability**: Handles multiple concurrent users

## 🎨 Sketch Pages

The original HTML prototypes are preserved and accessible at `/sketch/`:

- **Dashboard Designs**: Original design concepts
- **Public Pages**: Community-facing pages
- **Documentation**: Design and planning documents
- **Prototypes**: Early development versions

Access via:
- **Production**: `yourdomain.com/sketch/`
- **Local**: `http://localhost:8000/sketch/`

## 🤝 Contributing

### Development Guidelines
1. Follow the existing code structure
2. Maintain role-based access control
3. Test on mobile devices
4. Ensure database compatibility
5. Document new features

### Code Standards
- **PHP**: PSR-4 autoloading, PSR-12 coding style
- **HTML**: Semantic markup, accessibility
- **CSS**: BEM methodology, CSS custom properties
- **JavaScript**: ES6+, vanilla JS preferred

## 📝 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 🆘 Support

### Common Issues
1. **Database connection**: Ensure SQLite is enabled
2. **File permissions**: Check write access to `data/` directory
3. **Session issues**: Verify session configuration
4. **Mobile display**: Test responsive design

### Getting Help
- Check the documentation in `/docs/`
- Review the database schema
- Test with demo credentials
- Ensure PHP version compatibility

## 🔍 Health Check

Access the system health check at:
- **Production**: `yourdomain.com/health.php`
- **Local**: `http://localhost:8000/health.php`

This will verify:
- PHP version and extensions
- Database connectivity
- File permissions
- Server configuration

---

**Foz da Cova Dashboard** - Empowering community land projects with simple, effective management tools.

## 🚀 Quick Access

- **Main Application**: `/` (requires login)
- **Demo Version**: `/demo.html` (static HTML)
- **Sketch Pages**: `/sketch/` (original prototypes)
- **Health Check**: `/health.php` (system status)
- **Setup Script**: `/admin/setup.php` (database setup) 