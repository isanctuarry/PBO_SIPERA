<?php
namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $conf = [
                'host' => 'localhost',
                'dbname' => 'sistem_rapat',
                'user' => 'root',
                'pass' => ''
            ];
            $dsn = "mysql:host={$conf['host']};dbname={$conf['dbname']};charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];
            try {
                self::$instance = new PDO($dsn, $conf['user'], $conf['pass'], $options);
            } catch (PDOException $e) {
                throw new \App\Exceptions\HttpException(500, 'Database connection failed: ' . $e->getMessage());
            }
        }
        return self::$instance;
    }
}
