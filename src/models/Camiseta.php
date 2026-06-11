<?php

namespace Models;

use Config\Database;
use PDO;

class Camiseta
{
    public static function all()
    {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM camisetas ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM camisetas WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data)
    {
        $db = Database::getConnection();
        $query = "INSERT INTO camisetas (sku, titulo, club, pais, tipo, color, precio, precio_oferta, detalles, activa) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        $activa = isset($data['activa']) ? ($data['activa'] ? 1 : 0) : 1;
        
        $stmt->execute([
            $data['sku'],
            $data['titulo'],
            $data['club'],
            $data['pais'],
            $data['tipo'],
            $data['color'],
            $data['precio'],
            $data['precio_oferta'] ?? null,
            $data['detalles'] ?? null,
            $activa
        ]);
        return $db->lastInsertId();
    }

    public static function update($id, $data)
    {
        $db = Database::getConnection();
        $updates = [];
        $params = [];
        $allowed = ['sku', 'titulo', 'club', 'pais', 'tipo', 'color', 'precio', 'precio_oferta', 'detalles', 'activa'];
        
        foreach ($allowed as $field) {
            if (isset($data[$field])) {
                $updates[] = "$field = ?";
                $params[] = $field === 'activa' ? ($data[$field] ? 1 : 0) : $data[$field];
            }
        }
        
        if (empty($updates)) return false;
        
        $params[] = $id;
        $query = "UPDATE camisetas SET " . implode(", ", $updates) . " WHERE id = ?";
        $stmt = $db->prepare($query);
        return $stmt->execute($params);
    }

    public static function delete($id)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM camisetas WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
