<?php
namespace App\Core;

class Database {
    private static $instance = null;
    private $pdo;
    private $statement;
    private $transactions = 0;

    public function __construct() {
        $host = getenv('DB_HOST');
        $dbname = getenv('DB_NAME');
        $username = getenv('DB_USER');
        $password = getenv('DB_PASS');

        try {
            $this->pdo = new \PDO(
                "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
                $username,
                $password,
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_EMULATE_PREPARES => false,
                    \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
                ]
            );
        } catch (\PDOException $e) {
            throw new \Exception("Database connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function beginTransaction() {
        if ($this->transactions == 0) {
            $this->pdo->beginTransaction();
        }
        $this->transactions++;
        return $this;
    }

    public function commit() {
        if ($this->transactions > 0) {
            $this->transactions--;
            if ($this->transactions == 0) {
                $this->pdo->commit();
            }
        }
        return $this;
    }

    public function rollback() {
        if ($this->transactions > 0) {
            $this->transactions = 0;
            $this->pdo->rollBack();
        }
        return $this;
    }

    public function prepare($sql) {
        $this->statement = $this->pdo->prepare($sql);
        return $this->statement;
    }

    public function execute($params = []) {
        try {
            return $this->statement->execute($params);
        } catch (\PDOException $e) {
            throw new \Exception("Query execution failed: " . $e->getMessage());
        }
    }

    public function query($sql, $params = []) {
        $this->statement = $this->prepare($sql);
        $this->execute($params);
        return $this;
    }

    public function fetch() {
        return $this->statement->fetch();
    }

    public function fetchAll() {
        return $this->statement->fetchAll();
    }

    public function fetchColumn() {
        return $this->statement->fetchColumn();
    }

    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }

    public function rowCount() {
        return $this->statement->rowCount();
    }

    public function quote($value) {
        return $this->pdo->quote($value);
    }

    public function getPdo() {
        return $this->pdo;
    }
}