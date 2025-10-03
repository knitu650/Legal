#!/usr/bin/env php
<?php

/**
 * Storage Maintenance Script
 * Cleans temporary files, rotates logs, and manages cache
 */

require __DIR__ . '/../vendor/autoload.php';

use App\Services\Storage\StorageManager;
use App\Services\Storage\LogManager;
use App\Services\Storage\CacheManager;
use App\Services\Storage\SessionManager;

echo "=== STORAGE MAINTENANCE SCRIPT ===" . PHP_EOL;
echo "Started at: " . date('Y-m-d H:i:s') . PHP_EOL . PHP_EOL;

// Clean temporary files
echo "1. Cleaning temporary files..." . PHP_EOL;
$storageManager = new StorageManager();
$deletedTempFiles = $storageManager->cleanTempFiles();
echo "   Deleted {$deletedTempFiles} temporary file(s)" . PHP_EOL . PHP_EOL;

// Clean expired cache
echo "2. Cleaning expired cache..." . PHP_EOL;
$cacheManager = new CacheManager();
$deletedCache = $cacheManager->cleanExpired();
echo "   Deleted {$deletedCache} expired cache entries" . PHP_EOL . PHP_EOL;

// Clean old sessions
echo "3. Cleaning old sessions..." . PHP_EOL;
$sessionManager = new SessionManager();
$deletedSessions = $sessionManager->cleanOldSessions();
echo "   Deleted {$deletedSessions} old session(s)" . PHP_EOL . PHP_EOL;

// Rotate logs (if needed)
echo "4. Rotating logs..." . PHP_EOL;
$logManager = new LogManager();
$logManager->rotate();
echo "   Log rotation completed" . PHP_EOL . PHP_EOL;

// Display storage statistics
echo "5. Storage Statistics:" . PHP_EOL;
$stats = $storageManager->getStorageStats();

foreach ($stats as $type => $size) {
    $sizeFormatted = formatBytes($size);
    echo "   " . ucfirst($type) . ": {$sizeFormatted}" . PHP_EOL;
}

echo PHP_EOL;

// Display cache statistics
echo "6. Cache Statistics:" . PHP_EOL;
$cacheStats = $cacheManager->getStats();
echo "   Total entries: {$cacheStats['total_entries']}" . PHP_EOL;
echo "   Active entries: {$cacheStats['active_entries']}" . PHP_EOL;
echo "   Expired entries: {$cacheStats['expired_entries']}" . PHP_EOL;
echo "   Total size: {$cacheStats['total_size_formatted']}" . PHP_EOL;

echo PHP_EOL;

// Display session statistics
echo "7. Session Statistics:" . PHP_EOL;
$sessionStats = $sessionManager->getStats();
echo "   Total sessions: {$sessionStats['total_sessions']}" . PHP_EOL;
echo "   Active sessions: {$sessionStats['active_sessions']}" . PHP_EOL;
echo "   Expired sessions: {$sessionStats['expired_sessions']}" . PHP_EOL;
echo "   Total size: {$sessionStats['total_size_formatted']}" . PHP_EOL;

echo PHP_EOL;
echo "=== MAINTENANCE COMPLETED ===" . PHP_EOL;
echo "Finished at: " . date('Y-m-d H:i:s') . PHP_EOL;

function formatBytes($bytes)
{
    $units = ['B', 'KB', 'MB', 'GB'];
    
    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
        $bytes /= 1024;
    }
    
    return round($bytes, 2) . ' ' . $units[$i];
}
