<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;
use Exception;

/**
 * Database Class - PDO Singleton
 * Manages database connections using PDO with strict configuration
 */
class Database
{
    private static ?Database $instance = null;
    private PDO $connection;
    private array $config;
    
    private function __construct()
    {
        $this->config = require dirname(__DIR__, 2) . '/config/config.php';
        $this->connect();
    }
    
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function connect(): void
    {
        $dbConfig = $this->config['database'];
        
        try {
            $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}";
            
            $this->connection = new PDO(
                $dsn,
                $dbConfig['username'],
                $dbConfig['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_STRINGIFY_FETCHES => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$dbConfig['charset']} COLLATE {$dbConfig['charset']}_unicode_ci"
                ]
            );
            
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }
    
    public function getConnection(): PDO
    {
        return $this->connection;
    }
    
    public function prepare(string $sql): \PDOStatement
    {
        return $this->connection->prepare($sql);
    }
    
    public function query(string $sql): \PDOStatement
    {
        return $this->connection->query($sql);
    }
    
    public function lastInsertId(?string $name = null): string
    {
        return $this->connection->lastInsertId($name);
    }
    
    public function beginTransaction(): bool
    {
        return $this->connection->beginTransaction();
    }
    
    public function commit(): bool
    {
        return $this->connection->commit();
    }
    
    public function rollBack(): bool
    {
        return $this->connection->rollBack();
    }
    
    public function inTransaction(): bool
    {
        return $this->connection->inTransaction();
    }
    
    // Prevent cloning and unserialization
    private function __clone() {}
    public function __wakeup(): void
    {
        throw new Exception("Cannot unserialize singleton");
    }
}
