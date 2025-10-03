<?php

/**
 * Installation Script
 * Run this script to set up the database
 */

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Helpers/functions.php';

echo "Legal Document Management System - Installation\n";
echo "================================================\n\n";

// Load database config
$config = require __DIR__ . '/../config/database.php';
$dbConfig = $config['connections'][$config['default']];

try {
    // Connect to MySQL server
    $pdo = new PDO(
        "mysql:host={$dbConfig['host']};port={$dbConfig['port']}",
        $dbConfig['username'],
        $dbConfig['password']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✓ Connected to MySQL server\n";
    
    // Create database
    $dbName = $dbConfig['database'];
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `$dbName`");
    
    echo "✓ Database created/selected: $dbName\n";
    
    // Run migrations
    $migrationPath = __DIR__ . '/../database/migrations/';
    $migrations = glob($migrationPath . '*.php');
    sort($migrations);
    
    echo "\nRunning migrations...\n";
    
    foreach ($migrations as $migration) {
        $fileName = basename($migration);
        echo "  - Running $fileName...";
        
        $sql = require $migration;
        
        // Execute SQL (handle multiple statements)
        $statements = explode(';', $sql);
        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (!empty($statement)) {
                $pdo->exec($statement);
            }
        }
        
        echo " ✓\n";
    }
    
    echo "\n✓ All migrations completed successfully!\n";
    
    // Create default admin user
    echo "\nCreating default admin user...\n";
    
    $password = password_hash('admin123', PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("
        INSERT INTO users (name, email, password, role_id, status, email_verified_at)
        VALUES (?, ?, ?, ?, ?, NOW())
        ON DUPLICATE KEY UPDATE email = email
    ");
    $stmt->execute(['Admin User', 'admin@legaldocs.com', $password, 1, 'active']);
    
    echo "  Email: admin@legaldocs.com\n";
    echo "  Password: admin123\n";
    echo "  ⚠ Please change this password after first login!\n";
    
    // Create storage directories
    echo "\nCreating storage directories...\n";
    
    $directories = [
        'storage/app/documents',
        'storage/app/signatures',
        'storage/app/uploads',
        'storage/logs',
        'storage/cache',
        'storage/sessions',
        'public/assets/uploads',
    ];
    
    foreach ($directories as $dir) {
        $path = __DIR__ . '/../' . $dir;
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
            echo "  ✓ Created: $dir\n";
        }
    }
    
    echo "\n================================================\n";
    echo "Installation completed successfully!\n";
    echo "================================================\n";
    echo "\nNext steps:\n";
    echo "1. Configure your .env file\n";
    echo "2. Set up your web server to point to the 'public' directory\n";
    echo "3. Visit your application URL\n";
    echo "4. Login with the admin credentials above\n";
    echo "\n";
    
} catch (PDOException $e) {
    echo "\n✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
