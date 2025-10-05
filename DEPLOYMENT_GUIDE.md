# Deployment Guide

## Prerequisites

- PHP 8.0 or higher
- MySQL 8.0 or higher
- Apache/Nginx web server
- Composer (for dependency management)
- Git (optional, for version control)

## Server Requirements

### PHP Extensions Required
- PDO
- PDO_MySQL
- mbstring
- openssl
- curl
- json
- fileinfo
- gd or imagick (for image processing)

### Recommended Server Configuration
- Memory Limit: 256M or higher
- Max Execution Time: 300 seconds
- Max Upload File Size: 50M
- PHP Error Reporting: Off (in production)

## Installation Steps

### 1. Clone or Upload Files

```bash
git clone <repository-url>
# OR
# Upload files via FTP/SFTP to your web server
```

### 2. Configure Web Server

#### Apache Configuration

Create `.htaccess` in root directory (already included):

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

Set document root to `/public` directory or use the .htaccess redirect.

#### Nginx Configuration

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/project/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(ht|git) {
        deny all;
    }
}
```

### 3. Set File Permissions

```bash
# Make storage directories writable
chmod -R 775 storage/
chmod -R 775 storage/logs/
chmod -R 775 storage/cache/
chmod -R 775 storage/sessions/
chmod -R 775 public/assets/uploads/

# Set ownership (replace www-data with your web server user)
chown -R www-data:www-data storage/
chown -R www-data:www-data public/assets/uploads/
```

### 4. Configure Environment

```bash
# Copy environment example file
cp .env.example .env

# Edit .env file with your settings
nano .env
```

Configure the following in `.env`:

```env
# Application
APP_NAME="Legal Document Management System"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_HOST=localhost
DB_PORT=3306
DB_NAME=your_database_name
DB_USER=your_database_user
DB_PASS=your_database_password

# Mail
MAIL_DRIVER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"

# Payment Gateways
RAZORPAY_KEY=your_razorpay_key
RAZORPAY_SECRET=your_razorpay_secret

STRIPE_KEY=your_stripe_key
STRIPE_SECRET=your_stripe_secret

PAYTM_MERCHANT_ID=your_paytm_merchant_id
PAYTM_MERCHANT_KEY=your_paytm_merchant_key

# SMS Gateway
TWILIO_SID=your_twilio_sid
TWILIO_TOKEN=your_twilio_token
TWILIO_FROM=your_twilio_number

# Google Maps
GOOGLE_MAPS_API_KEY=your_google_maps_key

# Zoom Integration
ZOOM_API_KEY=your_zoom_api_key
ZOOM_API_SECRET=your_zoom_api_secret
```

### 5. Install Dependencies

```bash
# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Install Node dependencies (if needed)
npm install --production
```

### 6. Create Database

```bash
# Login to MySQL
mysql -u root -p

# Create database
CREATE DATABASE your_database_name CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Create user and grant privileges
CREATE USER 'your_database_user'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON your_database_name.* TO 'your_database_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 7. Run Database Migrations

```bash
# Run migrations to create tables
php scripts/migrate.php

# OR import SQL file directly
mysql -u your_database_user -p your_database_name < database/schema.sql
```

### 8. Seed Initial Data

```bash
# Run seeders to populate initial data
php scripts/seed.php
```

This will create:
- Default roles (Super Admin, Admin, MIS, Franchise, Lawyer, User)
- Default permissions
- Sample users (admin@legaldocs.com / Admin@123)
- Document categories
- Document templates
- Subscription plans
- Location data

### 9. Configure Cron Jobs

Add the following cron jobs for automated tasks:

```bash
# Edit crontab
crontab -e

# Add these lines
# Generate daily reports (runs at 2 AM)
0 2 * * * php /path/to/project/scripts/generate-reports.php

# Clean up temporary files (runs every 6 hours)
0 */6 * * * php /path/to/project/scripts/cleanup.php

# Update subscriptions (runs daily at 1 AM)
0 1 * * * php /path/to/project/scripts/update-subscriptions.php

# Backup database (runs daily at 3 AM)
0 3 * * * php /path/to/project/scripts/backup.php
```

### 10. SSL Certificate (Recommended)

Install SSL certificate using Let's Encrypt:

```bash
# Install Certbot
sudo apt-get install certbot python3-certbot-apache

# Get certificate
sudo certbot --apache -d yourdomain.com -d www.yourdomain.com

# Auto-renewal (already configured by certbot)
sudo certbot renew --dry-run
```

### 11. Test Installation

Visit your domain:
- Homepage: https://yourdomain.com
- Admin Login: https://yourdomain.com/login
- Test with default credentials: admin@legaldocs.com / Admin@123

### 12. Security Hardening

```bash
# Disable directory listing
# Already configured in .htaccess

# Hide PHP version
# Add to php.ini
expose_php = Off

# Restrict access to sensitive directories
# Configure in .htaccess or web server config

# Enable firewall
sudo ufw enable
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw allow 22/tcp
```

### 13. Performance Optimization

```bash
# Enable OPcache (add to php.ini)
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=10000

# Enable compression (add to .htaccess)
# Already configured

# Set up Redis/Memcached for caching (optional)
```

## Post-Deployment Checklist

- [ ] Verify all pages load correctly
- [ ] Test user registration and login
- [ ] Test document creation
- [ ] Test payment gateway integration
- [ ] Test email notifications
- [ ] Test SMS notifications
- [ ] Verify cron jobs are running
- [ ] Check error logs for any issues
- [ ] Test backup and restore procedures
- [ ] Configure monitoring and alerting
- [ ] Update default admin password
- [ ] Review and update system settings
- [ ] Test all user roles and permissions
- [ ] Verify SSL certificate installation
- [ ] Test mobile responsiveness
- [ ] Perform security audit

## Troubleshooting

### Common Issues

**Issue: 500 Internal Server Error**
- Check PHP error logs: `/var/log/apache2/error.log` or `/var/log/nginx/error.log`
- Verify file permissions on storage directories
- Check .htaccess syntax
- Enable display_errors temporarily to see error details

**Issue: Database Connection Failed**
- Verify database credentials in .env
- Check if MySQL service is running: `sudo systemctl status mysql`
- Verify user has proper privileges
- Check if database exists

**Issue: Cannot upload files**
- Check upload_max_filesize in php.ini
- Verify directory permissions
- Check disk space

**Issue: Emails not sending**
- Verify SMTP settings in .env
- Check firewall is not blocking port 587
- Test with mail-tester.com
- Check email logs in storage/logs/

**Issue: Payment gateway not working**
- Verify API keys are correct
- Check if in test/production mode
- Review payment gateway logs
- Verify callback URLs are accessible

## Monitoring

### Log Files
- Application: `storage/logs/app.log`
- Error: `storage/logs/error.log`
- Payment: `storage/logs/payment.log`
- API: `storage/logs/api.log`
- Audit: `storage/logs/audit.log`

### Monitoring Tools
- Set up Uptime monitoring (UptimeRobot, Pingdom)
- Configure error tracking (Sentry, Rollbar)
- Set up performance monitoring (New Relic, DataDog)
- Configure log aggregation (ELK Stack, Papertrail)

## Backup Strategy

### Daily Backups
- Database backup (automated via cron)
- File system backup (documents, uploads)
- Store backups off-site (S3, Google Cloud, etc.)

### Backup Script
```bash
# Manual backup
php scripts/backup.php

# Restore from backup
php scripts/restore.php --file=backup_filename.sql
```

## Support

For technical support:
- Email: support@legaldocs.com
- Phone: +91 9876543210
- Documentation: https://docs.legaldocs.com
- Issue Tracker: https://github.com/legaldocs/issues

## Version Updates

To update to a new version:

```bash
# Backup first!
php scripts/backup.php

# Pull latest changes
git pull origin main

# Update dependencies
composer install --no-dev --optimize-autoloader

# Run migrations
php scripts/migrate.php

# Clear cache
rm -rf storage/cache/*

# Test thoroughly
```
