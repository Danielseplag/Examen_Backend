<?php

namespace Models;

use Config\Database;
use PDO;

class Talla
{
    public static function all()
    {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM tallas ORDER BY id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM tallas WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data)
    {
        // CRUD basico
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
