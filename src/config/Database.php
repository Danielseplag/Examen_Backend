<?php

namespace Config;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $connection = null;

    /**
     * Obtener conexión a la base de datos
     */
    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            self::connect();
        }
        return self::$connection;
    }

    /**
     * Conectarse a la base de datos
     */
    private static function connect(): void
    {
        try {
            $host = $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?? 'mysql';
            $dbname = $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?? 'todo_camisetas';
            $user = $_ENV['DB_USER'] ?? getenv('DB_USER') ?? 'root';
            $password = $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD') ?? 'rootpass';

            $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";

            self::$connection = new PDO($dsn, $user, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);

            // Establecer zona horaria
            self::$connection->exec("SET time_zone = '-03:00'");

        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode([
                'error' => 'Error de conexión a la base de datos',
                'message' => $e->getMessage(),
            ]);
            exit();
        }
    }

    /**
     * Cerrar conexión
     */
    public static function closeConnection(): void
    {
        self::$connection = null;
    }
}
