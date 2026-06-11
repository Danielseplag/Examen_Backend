<?php

namespace Models;

use Config\Database;
use PDO;

class Orden
{
    public static function all()
    {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM ordenes ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM ordenes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data)
    {
        // La logica de transaccion compleja queda mejor en el controller por ahora
        return false;
    }

    public static function update($id, $data)
    {
        return false;
    }

    public static function delete($id)
    {
        return false;
    }
}
