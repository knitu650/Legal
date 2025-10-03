# Legal Document Management System - Installation Guide

## System Requirements

- **PHP**: 8.0 or higher
- **MySQL**: 8.0 or higher
- **Apache/Nginx**: Web server with mod_rewrite enabled
- **Composer**: PHP dependency manager
- **Extensions**: PDO, JSON, mbstring

## Installation Steps

### 1. Clone/Download the Project

```bash
cd /var/www/html
# Or download and extract the project files
```

### 2. Set Up Environment File

```bash
cp .env.example .env
```

Edit `.env` file with your configuration:

```env
# Database Configuration
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=legal_docs
DB_USERNAME=root
DB_PASSWORD=your_password

# Application Configuration
APP_URL=http://localhost
APP_DEBUG=true
APP_ENV=development
```

### 3. Install Dependencies

```bash
composer install
```

### 4. Run Installation Script

```bash
php scripts/install.php
```

This will:
- Create the database
- Run all migrations
- Create default admin user
- Set up storage directories

### 5. Configure Web Server

#### Apache (.htaccess already included)

Point your document root to the `public/` directory:

```apache
<VirtualHost *:80>
    ServerName legaldocs.local
    DocumentRoot /var/www/html/legal-docs/public
    
    <Directory /var/www/html/legal-docs/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

#### Nginx

```nginx
server {
    listen 80;
    server_name legaldocs.local;
    root /var/www/html/legal-docs/public;
    
    index index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### 6. Set Permissions

```bash
chmod -R 755 storage/
chmod -R 755 public/assets/uploads/
chown -R www-data:www-data storage/
chown -R www-data:www-data public/assets/uploads/
```

### 7. Access the Application

Visit: `http://localhost` or your configured domain

**Default Admin Credentials:**
- Email: `admin@legaldocs.com`
- Password: `admin123`

⚠️ **IMPORTANT**: Change the default password immediately after first login!

## Post-Installation

### 1. Configure Payment Gateways

Edit `.env` file:

```env
# Razorpay
RAZORPAY_KEY=your_key
RAZORPAY_SECRET=your_secret

# Stripe
STRIPE_KEY=your_key
STRIPE_SECRET=your_secret
```

### 2. Configure Email

```env
MAIL_DRIVER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
```

### 3. Configure SMS

```env
SMS_DRIVER=twilio
SMS_SID=your_sid
SMS_TOKEN=your_token
SMS_FROM=your_phone_number
```

### 4. Set Up Cron Jobs (Optional)

Add to crontab for automated tasks:

```bash
* * * * * php /var/www/html/legal-docs/scripts/cron.php
```

## Troubleshooting

### Database Connection Error

- Verify database credentials in `.env`
- Ensure MySQL service is running
- Check if database exists

### Permission Denied Errors

```bash
sudo chown -R www-data:www-data storage/
sudo chmod -R 755 storage/
```

### 404 Errors on All Pages

- Ensure mod_rewrite is enabled (Apache)
- Check `.htaccess` file exists in `public/`
- Verify web server configuration

### Blank Page / White Screen

- Enable debug mode: `APP_DEBUG=true` in `.env`
- Check PHP error logs
- Verify all required PHP extensions are installed

## Additional Configuration

### SSL Certificate (Production)

```bash
sudo certbot --apache -d yourdomain.com
```

### Optimize for Production

In `.env`:
```env
APP_ENV=production
APP_DEBUG=false
```

Clear cache:
```bash
php scripts/cache-clear.php
```

## Support

For issues and support:
- Email: support@legaldocs.com
- Documentation: `/docs`

## Security Notes

1. Never commit `.env` file to version control
2. Change default admin password
3. Use strong database passwords
4. Enable SSL in production
5. Keep software updated
6. Regular database backups

## Backup

```bash
# Database backup
mysqldump -u root -p legal_docs > backup.sql

# Full backup
tar -czf backup.tar.gz /var/www/html/legal-docs
```

## Updates

To update the system:

```bash
git pull origin main
composer update
php scripts/migrate.php
```
