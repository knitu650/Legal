# Storage System Documentation

## Complete Storage Directory Structure

```
storage/
├── app/                                    # Application Storage
│   ├── documents/                         # Generated Documents
│   │   ├── 2025/                         # Year-wise organization
│   │   │   ├── 01/                       # January
│   │   │   ├── 02/                       # February
│   │   │   ├── 03/                       # March
│   │   │   └── ... (up to 12)
│   │   └── temp/                         # Temporary documents (auto-deleted after 24h)
│   │
│   ├── signatures/                        # E-signature files
│   ├── uploads/                           # User uploaded files
│   └── exports/                           # Exported reports (CSV, PDF, Excel)
│
├── logs/                                   # Application Logs
│   ├── app.log                           # General application logs
│   ├── error.log                         # Error logs
│   ├── access.log                        # HTTP access logs
│   ├── payment.log                       # Payment transaction logs
│   ├── api.log                           # API request/response logs
│   └── audit.log                         # Security audit logs
│
├── cache/                                  # Cache Files
│   ├── views/                            # Compiled view templates
│   ├── routes/                           # Cached routes
│   └── data/                             # Data cache
│
└── sessions/                               # PHP Session Files
```

---

## 📦 Storage Manager Service

### Usage Examples

```php
use App\Services\Storage\StorageManager;

$storage = new StorageManager();

// Store document (auto-organized by year/month)
$result = $storage->storeDocument($_FILES['document'], $userId);
// Returns: ['success' => true, 'path' => 'documents/2025/01/user_123_contract_12345.pdf']

// Store temporary document
$result = $storage->storeTempDocument($_FILES['temp_file']);

// Store signature
$result = $storage->storeSignature($base64SignatureData, $userId);

// Store user upload
$result = $storage->storeUpload($_FILES['file'], $userId);

// Store exported report
$result = $storage->storeExport($csvContent, 'monthly-report', 'csv');

// Delete file
$storage->delete('documents/2025/01/file.pdf');

// Get file info
$info = $storage->getFileInfo('documents/2025/01/file.pdf');

// Clean temp files (older than 24h)
$deletedCount = $storage->cleanTempFiles();

// Get storage statistics
$stats = $storage->getStorageStats();
/*
Returns:
[
    'documents' => 12345678,  // bytes
    'signatures' => 234567,
    'uploads' => 456789,
    'exports' => 123456,
    'total' => 13160490
]
*/
```

---

## 📝 Log Manager Service

### Available Log Types

1. **app.log** - General application events
2. **error.log** - Errors and exceptions
3. **access.log** - HTTP requests
4. **payment.log** - Payment transactions
5. **api.log** - API calls
6. **audit.log** - Security events

### Usage Examples

```php
use App\Services\Storage\LogManager;

$logger = new LogManager();

// Application log
$logger->app('INFO', 'User logged in', ['user_id' => 123]);

// Error log
$logger->error('Database connection failed', ['host' => 'localhost']);

// Access log
$logger->access('GET', '/api/users', 200, '192.168.1.1', 123);

// Payment log
$logger->payment('success', 'Payment completed', [
    'amount' => 1000,
    'transaction_id' => 'TXN123'
]);

// API log
$logger->api('/api/v1/documents', 'POST', 201, 45);

// Audit log
$logger->audit(123, 'CREATE', 'Document', ['id' => 456]);

// Read log file (last 100 lines)
$lines = $logger->read('app.log', 100);

// Clear log file
$logger->clear('error.log');

// Get all logs with statistics
$logs = $logger->getAllLogs();

// Rotate logs (archive old logs)
$logger->rotate();
```

---

## 💾 Cache Manager Service

### Usage Examples

```php
use App\Services\Storage\CacheManager;

$cache = new CacheManager();

// Set cache (with TTL in seconds)
$cache->set('user_123', $userData, 3600);

// Get cache
$data = $cache->get('user_123', $defaultValue);

// Check if exists
if ($cache->has('user_123')) {
    // ...
}

// Delete cache
$cache->forget('user_123');

// Clear all cache
$cache->flush();

// Remember (get or set)
$users = $cache->remember('all_users', 3600, function() {
    return User::all();
});

// Remember forever
$config = $cache->rememberForever('app_config', function() {
    return loadConfig();
});

// Increment/Decrement
$cache->increment('page_views');
$cache->decrement('stock_count', 5);

// Cache views
$cache->cacheView('home.index', $compiledHTML);
$cachedView = $cache->getCachedView('home.index');

// Cache routes
$cache->cacheRoutes($routesArray);
$routes = $cache->getCachedRoutes();

// Clean expired cache
$cleaned = $cache->cleanExpired();

// Get statistics
$stats = $cache->getStats();
/*
Returns:
[
    'total_entries' => 150,
    'active_entries' => 145,
    'expired_entries' => 5,
    'total_size' => 2048576,
    'total_size_formatted' => '2.00 MB'
]
*/
```

---

## 🔐 Session Manager Service

### Usage Examples

```php
use App\Services\Storage\SessionManager;

$session = new SessionManager();

// Start session
$session->start();

// Set session value
$session->set('user_id', 123);
$session->set('user', $userData);

// Get session value
$userId = $session->get('user_id');
$user = $session->get('user', $defaultValue);

// Check if exists
if ($session->has('user_id')) {
    // ...
}

// Remove value
$session->remove('user_id');

// Flash message (one-time)
$session->flash('success', 'Profile updated successfully');
$message = $session->getFlash('success');

// Regenerate session ID (security)
$session->regenerate();

// Get all session data
$allData = $session->all();

// Get session ID
$sessionId = $session->getId();

// Destroy session (logout)
$session->destroy();

// Clean old sessions
$cleaned = $session->cleanOldSessions();

// Get statistics
$stats = $session->getStats();
/*
Returns:
[
    'total_sessions' => 50,
    'active_sessions' => 45,
    'expired_sessions' => 5,
    'total_size' => 102400,
    'total_size_formatted' => '100.00 KB'
]
*/
```

---

## 🛠️ Maintenance Scripts

### 1. Storage Maintenance Script

Runs automatic cleanup tasks:

```bash
php scripts/storage-maintenance.php
```

**What it does:**
- ✅ Cleans temporary files older than 24 hours
- ✅ Removes expired cache entries
- ✅ Deletes old session files
- ✅ Rotates log files
- ✅ Shows storage statistics

**Schedule with Cron:**
```cron
# Run daily at 2 AM
0 2 * * * /usr/bin/php /path/to/scripts/storage-maintenance.php
```

### 2. Storage Backup Script

Creates compressed backup of entire storage directory:

```bash
php scripts/storage-backup.php
```

**What it does:**
- ✅ Creates tar.gz archive of storage/
- ✅ Saves to backups/ directory
- ✅ Deletes backups older than 7 days
- ✅ Shows backup size and location

**Schedule with Cron:**
```cron
# Run daily at 3 AM
0 3 * * * /usr/bin/php /path/to/scripts/storage-backup.php
```

---

## 🔒 Security & Permissions

### Recommended Permissions

```bash
# Set correct permissions
chmod -R 755 storage/
chmod -R 775 storage/app
chmod -R 775 storage/logs
chmod -R 775 storage/cache
chmod -R 775 storage/sessions

# Set correct ownership
chown -R www-data:www-data storage/
```

### Security Best Practices

1. **Never make storage/ web-accessible**
   - Keep it outside public/
   - Use controllers to serve files

2. **Validate all uploads**
   ```php
   // Check file type
   $allowedTypes = ['pdf', 'doc', 'docx'];
   if (!in_array($extension, $allowedTypes)) {
       throw new Exception('Invalid file type');
   }
   
   // Check file size
   if ($fileSize > 10 * 1024 * 1024) { // 10MB
       throw new Exception('File too large');
   }
   ```

3. **Sanitize filenames**
   - Remove special characters
   - Use unique names
   - Prevent directory traversal

4. **Encrypt sensitive files**
   ```php
   $encrypted = SecurityHelper::encrypt($fileContent);
   file_put_contents($path, $encrypted);
   ```

---

## 📊 Storage Monitoring

### Check Storage Usage

```php
$storage = new StorageManager();
$stats = $storage->getStorageStats();

foreach ($stats as $type => $bytes) {
    echo "$type: " . formatBytes($bytes) . "\n";
}
```

### Monitor Log Files

```php
$logger = new LogManager();
$logs = $logger->getAllLogs();

foreach ($logs as $file => $info) {
    echo "$file: {$info['size_formatted']} ({$info['lines']} lines)\n";
}
```

### Check Cache Health

```php
$cache = new CacheManager();
$stats = $cache->getStats();

echo "Cache hit rate: " . 
     ($stats['active_entries'] / $stats['total_entries'] * 100) . "%\n";
```

---

## 🚀 Performance Optimization

### 1. Cache Frequently Accessed Data

```php
// Cache database queries
$users = $cache->remember('active_users', 3600, function() {
    return User::where('status', 'active')->get();
});
```

### 2. Use Compiled Views

```php
// Compile and cache views
$cache->cacheView('dashboard', $compiledHTML);
```

### 3. Implement Log Rotation

```php
// Rotate logs weekly
$logger->rotate();
```

### 4. Clean Temp Files Regularly

```php
// Run daily
$storage->cleanTempFiles();
```

---

## 📈 Backup & Recovery

### Manual Backup

```bash
# Full storage backup
tar -czf storage-backup.tar.gz storage/

# Specific directories
tar -czf documents-backup.tar.gz storage/app/documents/
tar -czf logs-backup.tar.gz storage/logs/
```

### Restore from Backup

```bash
# Extract backup
tar -xzf storage-backup.tar.gz

# Or restore specific directory
tar -xzf documents-backup.tar.gz -C /path/to/restore/
```

---

## ✅ Complete Feature Set

**Storage Manager:**
- ✅ Auto-organized document storage (year/month)
- ✅ Temporary file management
- ✅ Signature storage
- ✅ Upload handling
- ✅ Export management
- ✅ File deletion
- ✅ Storage statistics

**Log Manager:**
- ✅ Multiple log types
- ✅ Structured logging
- ✅ Log reading
- ✅ Log clearing
- ✅ Log rotation
- ✅ Log statistics

**Cache Manager:**
- ✅ TTL-based caching
- ✅ Remember functions
- ✅ View caching
- ✅ Route caching
- ✅ Increment/Decrement
- ✅ Automatic expiration cleanup
- ✅ Cache statistics

**Session Manager:**
- ✅ Secure session handling
- ✅ Session regeneration
- ✅ Flash messages
- ✅ Old session cleanup
- ✅ Session statistics

**Maintenance Scripts:**
- ✅ Automated cleanup
- ✅ Automated backups
- ✅ Detailed reporting

---

## 🎯 Ready for Production!

All storage components are fully functional and production-ready with:
- Complete error handling
- Security best practices
- Performance optimization
- Automated maintenance
- Comprehensive logging
- Easy monitoring
