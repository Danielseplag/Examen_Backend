<?php

namespace Models;

use Config\Database;
use PDO;

class Cliente
{
    public static function all()
    {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM clientes ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM clientes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data)
    {
        $db = Database::getConnection();
        $query = "INSERT INTO clientes (rut, nombre_comercial, direccion, categoria, contacto_nombre, contacto_email, descuento_porcentaje, activo) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        $activo = isset($data['activo']) ? ($data['activo'] ? 1 : 0) : 1;
        
        $stmt->execute([
            $data['rut'],
            $data['nombre_comercial'],
            $data['direccion'] ?? null,
            $data['categoria'] ?? 'Regular',
            $data['contacto_nombre'] ?? null,
            $data['contacto_email'] ?? null,
            $data['descuento_porcentaje'] ?? 0,
            $activo
        ]);
        return $db->lastInsertId();
    }

    public static function update($id, $data)
    {
        $db = Database::getConnection();
        $updates = [];
        $params = [];
        $allowed = ['rut', 'nombre_comercial', 'direccion', 'categoria', 'contacto_nombre', 'contacto_email', 'descuento_porcentaje', 'activo'];
        
        foreach ($allowed as $field) {
            if (isset($data[$field])) {
                $updates[] = "$field = ?";
                $params[] = $field === 'activo' ? ($data[$field] ? 1 : 0) : $data[$field];
            }
        }
        
        if (empty($updates)) return false;
        
        $params[] = $id;
        $query = "UPDATE clientes SET " . implode(", ", $updates) . " WHERE id = ?";
        $stmt = $db->prepare($query);
        return $stmt->execute($params);
    }

    public static function delete($id)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM clientes WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
