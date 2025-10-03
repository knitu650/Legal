#!/usr/bin/env php
<?php

/**
 * Storage Backup Script
 * Creates compressed backup of storage directory
 */

$storagePath = __DIR__ . '/../storage/';
$backupPath = __DIR__ . '/../backups/';
$timestamp = date('Y-m-d_His');
$backupFile = $backupPath . "storage-backup-{$timestamp}.tar.gz";

echo "=== STORAGE BACKUP SCRIPT ===" . PHP_EOL;
echo "Started at: " . date('Y-m-d H:i:s') . PHP_EOL . PHP_EOL;

// Create backup directory
if (!is_dir($backupPath)) {
    mkdir($backupPath, 0755, true);
    echo "Created backup directory: {$backupPath}" . PHP_EOL;
}

// Create tar.gz archive
echo "Creating backup archive..." . PHP_EOL;
echo "Source: {$storagePath}" . PHP_EOL;
echo "Destination: {$backupFile}" . PHP_EOL . PHP_EOL;

$command = "tar -czf {$backupFile} -C " . dirname($storagePath) . " storage";
exec($command, $output, $returnCode);

if ($returnCode === 0) {
    $size = filesize($backupFile);
    $sizeFormatted = formatBytes($size);
    
    echo "✓ Backup completed successfully!" . PHP_EOL;
    echo "  Backup file: {$backupFile}" . PHP_EOL;
    echo "  Size: {$sizeFormatted}" . PHP_EOL;
    
    // Clean old backups (keep last 7 days)
    echo PHP_EOL . "Cleaning old backups (keeping last 7 days)..." . PHP_EOL;
    $files = glob($backupPath . 'storage-backup-*.tar.gz');
    $deleted = 0;
    
    foreach ($files as $file) {
        if (filemtime($file) < strtotime('-7 days')) {
            if (unlink($file)) {
                $deleted++;
                echo "  Deleted: " . basename($file) . PHP_EOL;
            }
        }
    }
    
    echo "Deleted {$deleted} old backup(s)" . PHP_EOL;
} else {
    echo "✗ Backup failed!" . PHP_EOL;
    echo "Error code: {$returnCode}" . PHP_EOL;
}

echo PHP_EOL . "=== BACKUP COMPLETED ===" . PHP_EOL;
echo "Finished at: " . date('Y-m-d H:i:s') . PHP_EOL;

function formatBytes($bytes)
{
    $units = ['B', 'KB', 'MB', 'GB'];
    
    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
        $bytes /= 1024;
    }
    
    return round($bytes, 2) . ' ' . $units[$i];
}
