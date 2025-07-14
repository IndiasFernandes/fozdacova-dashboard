# Foz da Cova Dashboard - Deployment Guide

## 🚀 Quick Deployment

### cPanel Deployment (Recommended)

1. **Upload Files**
   - Upload all files to your `public_html/` directory
   - Ensure the `data/` directory is outside web root for security

2. **Set Permissions**
   ```bash
   chmod 755 public_html/
   chmod 644 public_html/*.php
   chmod 755 data/
   chmod 644 data/foz_da_cova.db
   ```

3. **Database Setup**
   - Access your cPanel file manager
   - Navigate to `admin/setup.php`
   - Run the setup script via browser or SSH

4. **Configuration**
   - Update `includes/config.php` with your domain
   - Set proper database path
   - Configure session settings

### Local Development

1. **Install PHP**
   ```bash
   # macOS with Homebrew
   brew install php
   
   # Ubuntu/Debian
   sudo apt-get install php php-sqlite3
   
   # Windows with XAMPP
   # Download and install XAMPP
   ```

2. **Start Development Server**
   ```bash
   cd public/
   php -S localhost:8000
   ```

3. **Access Application**
   - Open `http://localhost:8000`
   - Login with demo credentials

## 🔧 Configuration

### Database Configuration

Edit `includes/config.php`:

```php
// Database path (adjust for your hosting)
define('DB_PATH', __DIR__ . '/../data/foz_da_cova.db');

// App settings
define('APP_NAME', 'Foz da Cova Dashboard');
define('APP_VERSION', '1.0.0');

// Session settings (enable HTTPS in production)
ini_set('session.cookie_secure', 0); // Set to 1 for HTTPS
```

### Web Server Configuration

#### Apache (.htaccess)
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]

# Security headers
Header always set X-Content-Type-Options nosniff
Header always set X-Frame-Options DENY
Header always set X-XSS-Protection "1; mode=block"
```

#### Nginx
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}

location ~ \.php$ {
    fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
    fastcgi_index index.php;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    include fastcgi_params;
}
```

## 🔒 Security Setup

### File Permissions
```bash
# Directories
find . -type d -exec chmod 755 {} \;

# Files
find . -type f -exec chmod 644 {} \;

# Database (readable by web server)
chmod 644 data/foz_da_cova.db
```

### Security Headers
Add to your `.htaccess` or server configuration:

```apache
# Security headers
Header always set X-Content-Type-Options nosniff
Header always set X-Frame-Options DENY
Header always set X-XSS-Protection "1; mode=block"
Header always set Referrer-Policy "strict-origin-when-cross-origin"
```

### Database Security
- Keep database file outside web root if possible
- Use strong passwords for production
- Regular backups
- Monitor access logs

## 📊 Performance Optimization

### PHP Configuration
```ini
; php.ini optimizations
memory_limit = 128M
max_execution_time = 30
upload_max_filesize = 10M
post_max_size = 10M
```

### Caching
```apache
# Browser caching for static assets
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
    ExpiresByType image/png "access plus 1 month"
    ExpiresByType image/jpg "access plus 1 month"
    ExpiresByType image/jpeg "access plus 1 month"
</IfModule>
```

### Compression
```apache
# Enable compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>
```

## 🔄 Backup Strategy

### Database Backup
```bash
# Manual backup
cp data/foz_da_cova.db data/backup_$(date +%Y%m%d_%H%M%S).db

# Automated backup script
#!/bin/bash
BACKUP_DIR="/path/to/backups"
DATE=$(date +%Y%m%d_%H%M%S)
cp data/foz_da_cova.db "$BACKUP_DIR/foz_da_cova_$DATE.db"
```

### File Backup
```bash
# Backup entire application
tar -czf foz_da_cova_backup_$(date +%Y%m%d).tar.gz .
```

## 🐛 Troubleshooting

### Common Issues

#### Database Connection Error
```php
// Check SQLite support
if (!extension_loaded('sqlite3')) {
    die('SQLite3 extension not loaded');
}

// Check file permissions
if (!is_writable(dirname(DB_PATH))) {
    die('Database directory not writable');
}
```

#### Session Issues
```php
// Check session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
session_start();
```

#### File Upload Issues
```php
// Check upload settings
echo "Upload max filesize: " . ini_get('upload_max_filesize') . "\n";
echo "Post max size: " . ini_get('post_max_size') . "\n";
```

### Error Logging
```php
// Enable error logging
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', '/path/to/error.log');
```

## 📱 Mobile Optimization

### Viewport Configuration
```html
<meta name="viewport" content="width=device-width, initial-scale=1.0">
```

### Touch-Friendly Design
```css
/* Minimum touch target size */
.btn, .nav-link {
    min-height: 44px;
    min-width: 44px;
}
```

## 🔍 Monitoring

### Health Check
Create `health.php`:
```php
<?php
// Database connection test
try {
    $db = new SQLite3('../data/foz_da_cova.db');
    echo "Database: OK\n";
} catch (Exception $e) {
    echo "Database: ERROR - " . $e->getMessage() . "\n";
}

// File permissions test
if (is_writable('../data/')) {
    echo "File permissions: OK\n";
} else {
    echo "File permissions: ERROR\n";
}

// PHP version
echo "PHP version: " . phpversion() . "\n";
?>
```

### Performance Monitoring
```php
// Add to pages for performance tracking
$start_time = microtime(true);
// ... page content ...
$end_time = microtime(true);
$load_time = $end_time - $start_time;
```

## 🚀 Production Checklist

- [ ] Database created and populated
- [ ] File permissions set correctly
- [ ] Security headers configured
- [ ] SSL certificate installed (for HTTPS)
- [ ] Error logging enabled
- [ ] Backup strategy implemented
- [ ] Performance optimizations applied
- [ ] Mobile testing completed
- [ ] User training completed
- [ ] Documentation updated

## 📞 Support

For deployment issues:
1. Check the error logs
2. Verify PHP configuration
3. Test database connectivity
4. Review file permissions
5. Contact hosting provider if needed

---

**Ready to deploy!** Follow this guide for a smooth deployment experience. 