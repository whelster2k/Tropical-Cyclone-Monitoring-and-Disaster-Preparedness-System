<?php

namespace App\Core;

use PDO;
use PDOException;

/**
 * Base model
 */
abstract class Model {
    /**
     * Get the PDO database connection
     *
     * @return PDO
     */
    protected static function getDB() {
        static $db = null;

        if ($db === null) {
            try {
                $host = $_ENV['DB_HOST'] ?? 'localhost';
                $database = $_ENV['DB_DATABASE'] ?? 'pagasa_cyclone';
                $username = $_ENV['DB_USERNAME'] ?? 'root';
                $password = $_ENV['DB_PASSWORD'] ?? '';
                $charset = 'utf8mb4';
                
                $dsn = "mysql:host={$host};dbname={$database};charset={$charset}";
                
                // Debug information
                error_log("Attempting database connection with:");
                error_log("DSN: " . $dsn);
                error_log("Username: " . $username);
                error_log("Host: " . $host);
                error_log("Database: " . $database);
                
                $db = new PDO($dsn, $username, $password);

                // Throw an Exception when an error occurs
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                // Use associative arrays for fetched rows
                $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            } catch (PDOException $e) {
                error_log("Database connection error: " . $e->getMessage());
                throw new \Exception('Database connection error: ' . $e->getMessage());
            }
        }

        return $db;
    }

    /**
     * Get all records
     *
     * @return array
     */
    public static function all() {
        $db = static::getDB();
        $table = static::getTable();
        
        $stmt = $db->query("SELECT * FROM {$table}");
        return $stmt->fetchAll();
    }

    /**
     * Find a record by ID
     *
     * @param int $id The record ID
     *
     * @return mixed The record found or false if not found
     */
    public static function find($id) {
        $db = static::getDB();
        $table = static::getTable();
        
        $stmt = $db->prepare("SELECT * FROM {$table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Create a new record
     *
     * @param array $data The data to insert
     *
     * @return int The ID of the newly created record
     */
    public static function create($data) {
        $db = static::getDB();
        $table = static::getTable();
        
        $columns = implode(', ', array_keys($data));
        $values = implode(', ', array_fill(0, count($data), '?'));
        
        $stmt = $db->prepare("INSERT INTO {$table} ({$columns}) VALUES ({$values})");
        $stmt->execute(array_values($data));
        
        return $db->lastInsertId();
    }

    /**
     * Update a record
     *
     * @param int $id The record ID
     * @param array $data The data to update
     *
     * @return bool True if success, false otherwise
     */
    public static function update($id, $data) {
        $db = static::getDB();
        $table = static::getTable();
        
        $set = implode(', ', array_map(function($key) {
            return "{$key} = ?";
        }, array_keys($data)));
        
        $stmt = $db->prepare("UPDATE {$table} SET {$set} WHERE id = ?");
        
        $values = array_values($data);
        $values[] = $id;
        
        return $stmt->execute($values);
    }

    /**
     * Delete a record
     *
     * @param int $id The record ID
     *
     * @return bool True if success, false otherwise
     */
    public static function delete($id) {
        $db = static::getDB();
        $table = static::getTable();
        
        $stmt = $db->prepare("DELETE FROM {$table} WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Find records by conditions
     *
     * @param array $conditions The conditions for the WHERE clause
     * @param array $params The parameter values for the conditions
     *
     * @return array
     */
    public static function where($conditions, $params = []) {
        $db = static::getDB();
        $table = static::getTable();
        
        $where = implode(' AND ', array_map(function($condition) {
            return "{$condition} = ?";
        }, array_keys($conditions)));
        
        $stmt = $db->prepare("SELECT * FROM {$table} WHERE {$where}");
        $stmt->execute(array_values($conditions));
        
        return $stmt->fetchAll();
    }

    /**
     * Execute a raw SQL query
     *
     * @param string $sql The SQL query
     * @param array $params The parameter values
     *
     * @return array|bool Query results or false on failure
     */
    protected static function raw($sql, $params = []) {
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        
        if ($stmt->execute($params)) {
            return $stmt->fetchAll();
        }
        
        return false;
    }

    /**
     * Get the table name for the model
     *
     * @return string
     */
    protected static function getTable() {
        $class = get_called_class();
        if (defined("$class::TABLE")) {
            return $class::TABLE;
        }
        
        // Convert CamelCase to snake_case and pluralize
        $table = preg_replace('/([a-z])([A-Z])/', '$1_$2', class_basename($class));
        return strtolower($table) . 's';
    }

    /**
     * Get class name without namespace
     *
     * @param string $class Full class name
     *
     * @return string
     */
    private static function class_basename($class) {
        $class = is_object($class) ? get_class($class) : $class;
        return basename(str_replace('\\', '/', $class));
    }
} 