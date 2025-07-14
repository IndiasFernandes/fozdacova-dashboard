# Foz da Cova Dashboard

A comprehensive community land project management system with role-based access control for Admin, Stewart, and WWOOFer roles.

## 🌱 Overview

Foz da Cova Dashboard is a lightweight, PHP-based web application designed for community land project management. It provides three distinct user interfaces:

- **Admin Dashboard**: Full system oversight, financial control, and strategic management
- **Stewart Dashboard**: Project coordination, team management, and resource flow
- **WWOOFer Dashboard**: Activity logging, project participation, and learning resources

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
├── public/                 # Web root (cPanel public_html)
│   ├── index.php          # Main router
│   ├── login.php          # Authentication
│   ├── logout.php         # Logout handler
│   ├── assets/            # CSS, JS, images
│   ├── admin/             # Admin pages
│   ├── stewart/           # Stewart pages
│   ├── wwoofer/           # WWOOFer pages
│   └── uploads/           # File uploads
├── includes/              # PHP includes
│   ├── config.php         # Database & app config
│   ├── auth.php           # Authentication logic
│   ├── db.php             # Database functions
│   └── templates/         # Template files
├── data/                  # Database & data files
│   └── foz_da_cova.db    # SQLite database
└── admin/                 # Admin-only files
    └── setup.php          # Database setup
```

## 🚀 Quick Start

### Prerequisites
- PHP 7.4 or higher
- SQLite support enabled
- Web server (Apache/Nginx)

### Installation

1. **Clone or download the project**
   ```bash
   git clone [repository-url]
   cd foz-da-cova
   ```

2. **Set up the database**
   ```bash
   php admin/setup.php
   ```

3. **Configure web server**
   - Point document root to the `public/` directory
   - Ensure PHP has write permissions to `data/` directory

4. **Access the application**
   - Navigate to your web server URL
   - Login with demo credentials (see below)

### Demo Credentials

| Role | Username | Password |
|------|----------|----------|
| Admin | admin | admin123 |
| Stewart | maria | maria123 |
| WWOOFer | joao | joao123 |
| WWOOFer | ana | ana123 |

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

### Key Features
- Role-based access control
- Activity logging with mood tracking
- Expense approval workflow
- Suggestion review system
- Project participation tracking
- Learning resource management

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
1. Upload files to `public_html/`
2. Set proper file permissions (755 for directories, 644 for files)
3. Ensure PHP has write access to `data/` directory
4. Run database setup script
5. Configure `.htaccess` for clean URLs

### Local Development
1. Install PHP and SQLite
2. Run `php -S localhost:8000 -t public/`
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

---

**Foz da Cova Dashboard** - Empowering community land projects with simple, effective management tools. 