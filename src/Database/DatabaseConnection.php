<?php

namespace BookStore\Database;

use PDO;
use PDOException;

class DatabaseConnection
{
    /**
     * @return PDO
     */
    public static function connect(): PDO
    {
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
            return new PDO($dsn, $user, $password, $options);
        }
        catch(PDOException $e) {
            throw new PDOException($e->getMessage());
        }
    }
}