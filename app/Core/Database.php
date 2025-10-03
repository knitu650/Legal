<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static $instance = null;
    private $connection;
    
    private function __construct()
    {
        $config = require __DIR__ . '/../../config/database.php';
        $dbConfig = $config['connections'][$config['default']];
        
        try {
            $dsn = "mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['database']};charset={$dbConfig['charset']}";
            
            $this->connection = new PDO(
                $dsn,
                $dbConfig['username'],
                $dbConfig['password'],
                $dbConfig['options']
            );
            
        } catch (PDOException $e) {
            throw new Exception\DatabaseException("Database connection failed: " . $e->getMessage());
        }
    }
    
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection()
    {
        return $this->connection;
    }
    
    public function beginTransaction()
    {
        return $this->connection->beginTransaction();
    }
    
    public function commit()
    {
        return $this->connection->commit();
    }
    
    public function rollBack()
    {
        return $this->connection->rollBack();
    }
}
