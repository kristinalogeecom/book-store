<?php

namespace BookStore\Infrastructure\Database;

use PDO;
use PDOException;

class DatabaseConnection
{
    private static ?PDO $instance = null;

    private function __construct() {}

    private function __clone() {}

    /**
     * Connects to the database
     *
     * @return PDO
     */
    public static function connect(): PDO
    {
        if (self::$instance === null) {
            $host = 'localhost';
            $db = 'bookstore';
            $user = 'root';
            $password = 'password';

            $dsn = "mysql:host=$host;dbname=$db";

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];

            try {
                self::$instance = new PDO($dsn, $user, $password, $options);
            } catch (PDOException $e) {
                throw new PDOException($e->getMessage());
            }
        }

        return self::$instance;
    }
}