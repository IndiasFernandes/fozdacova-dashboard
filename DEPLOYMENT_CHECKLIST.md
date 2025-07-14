# Foz da Cova Dashboard - Deployment Checklist

## 🚀 Pre-Deployment Checklist

### ✅ File Structure Verification
- [ ] `public_html/` contains all production files
- [ ] `sketch/` contains all original HTML prototypes
- [ ] Database setup script is in `public_html/admin/setup.php`
- [ ] Health check is in `public_html/health.php`
- [ ] Demo version is in `public_html/demo.html`

### ✅ File Permissions
```bash
# Directories
chmod 755 public_html/
chmod 755 public_html/admin/
chmod 755 public_html/stewart/
chmod 755 public_html/wwoofer/
chmod 755 public_html/includes/
chmod 755 public_html/data/
chmod 755 public_html/assets/
chmod 755 sketch/

# Files
chmod 644 public_html/*.php
chmod 644 public_html/*.html
chmod 644 public_html/.htaccess
chmod 644 sketch/.htaccess
chmod 644 public_html/data/foz_da_cova.db
```

### ✅ Database Setup
- [ ] SQLite3 extension is enabled
- [ ] Database directory is writable
- [ ] Setup script can create tables
- [ ] Sample data is populated

## 🌐 cPanel Deployment Steps

### Step 1: Upload Files
1. **Upload `public_html/` contents** to your `public_html/` directory
2. **Upload `sketch/`** to your root directory (outside web root)
3. **Verify file structure** matches expected layout

### Step 2: Set Permissions
```bash
# Via cPanel File Manager or SSH
find public_html/ -type d -exec chmod 755 {} \;
find public_html/ -type f -exec chmod 644 {} \;
chmod 755 public_html/data/
chmod 644 public_html/data/foz_da_cova.db
```

### Step 3: Database Setup
1. **Access setup script**: `yourdomain.com/admin/setup.php`
2. **Verify database creation**: Check for success message
3. **Test database connection**: Use health check

### Step 4: Configuration
1. **Update database path** in `includes/config.php` if needed
2. **Set session configuration** for your domain
3. **Enable HTTPS** in production (set `session.cookie_secure = 1`)

## 🔧 Local Development Setup

### Step 1: Install Dependencies
```bash
# macOS
brew install php

# Ubuntu/Debian
sudo apt-get install php php-sqlite3

# Windows
# Download XAMPP from https://www.apachefriends.org/
```

### Step 2: Start Development Server
```bash
cd public_html/
php -S localhost:8000
```

### Step 3: Access Application
- **Main App**: `http://localhost:8000/`
- **Demo**: `http://localhost:8000/demo.html`
- **Sketch**: `http://localhost:8000/sketch/`
- **Health**: `http://localhost:8000/health.php`

## 🧪 Testing Checklist

### ✅ Authentication Testing
- [ ] Login works with demo credentials
- [ ] Role-based access control functions
- [ ] Logout properly destroys session
- [ ] Unauthorized access is blocked

### ✅ Database Testing
- [ ] Database connection successful
- [ ] Tables created with correct schema
- [ ] Sample data is accessible
- [ ] CRUD operations work

### ✅ Interface Testing
- [ ] Admin dashboard loads correctly
- [ ] Stewart dashboard loads correctly
- [ ] WWOOFer dashboard loads correctly
- [ ] Mobile responsive design works
- [ ] Navigation functions properly

### ✅ Sketch Pages Testing
- [ ] `/sketch/` redirects to sketch directory
- [ ] Original HTML pages are accessible
- [ ] CSS and JS files load correctly
- [ ] Documentation is readable

### ✅ Security Testing
- [ ] Sensitive files are protected
- [ ] SQL injection prevention works
- [ ] XSS protection is active
- [ ] Session security is configured

## 🔍 Health Check Verification

### System Requirements
- [ ] PHP 7.4+ installed
- [ ] SQLite3 extension loaded
- [ ] File permissions correct
- [ ] Database writable

### Performance Checks
- [ ] Page load time < 2 seconds
- [ ] Database queries optimized
- [ ] Assets compressed
- [ ] Caching enabled

## 🚀 Production Deployment

### Final Steps
1. **Enable HTTPS** (SSL certificate)
2. **Set production error reporting** (disable display_errors)
3. **Configure backup strategy**
4. **Set up monitoring**
5. **Test all user roles**

### Post-Deployment Verification
- [ ] All URLs work correctly
- [ ] Database operations function
- [ ] File uploads work (if enabled)
- [ ] Email functionality works (if configured)
- [ ] Mobile devices display correctly

## 🐛 Troubleshooting

### Common Issues

#### Database Connection Failed
```bash
# Check SQLite support
php -m | grep sqlite

# Check file permissions
ls -la data/
chmod 755 data/
chmod 644 data/foz_da_cova.db
```

#### 404 Errors
```bash
# Check .htaccess
cat public_html/.htaccess

# Verify mod_rewrite enabled
# Contact hosting provider if needed
```

#### Permission Denied
```bash
# Set correct permissions
find . -type d -exec chmod 755 {} \;
find . -type f -exec chmod 644 {} \;
```

#### Session Issues
```php
// Check session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
session_start();
```

## 📞 Support Resources

### Documentation
- **README.md**: Complete system overview
- **DEPLOYMENT.md**: Detailed deployment guide
- **Health Check**: `/health.php` for system status

### Demo Access
- **Main App**: Login with demo credentials
- **Static Demo**: `/demo.html` (no login required)
- **Sketch Pages**: `/sketch/` (original prototypes)

### Demo Credentials
| Role | Username | Password |
|------|----------|----------|
| Admin | admin | admin123 |
| Stewart | maria | maria123 |
| WWOOFer | joao | joao123 |
| WWOOFer | ana | ana123 |

## ✅ Final Verification

Before going live:
- [ ] All tests pass
- [ ] Performance is acceptable
- [ ] Security measures are active
- [ ] Backup system is configured
- [ ] Documentation is complete
- [ ] Team is trained on system

---

**Ready for production!** 🚀

The Foz da Cova Dashboard is now a complete, functional system ready to empower your community land project. 