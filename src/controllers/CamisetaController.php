<?php

namespace Controllers;

use Config\Database;
use Config\Response;
use PDOException;

class CamisetaController
{
    /**
     * GET /api/camisetas - Listar todas las camisetas
     */
    public static function index(): void
    {
        try {
            $db = Database::getConnection();
            $query = "SELECT * FROM camisetas ORDER BY id DESC";
            $stmt = $db->prepare($query);
            $stmt->execute();
            $camisetas = $stmt->fetchAll();

            Response::success($camisetas);
        } catch (PDOException $e) {
            Response::error("Error al obtener camisetas: " . $e->getMessage(), 500);
        }
    }

    /**
     * GET /api/camisetas/{id} - Obtener una camiseta específica
     */
    public static function show($id): void
    {
        try {
            $db = Database::getConnection();
            $query = "SELECT * FROM camisetas WHERE id = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$id]);
            $camiseta = $stmt->fetch();

            if (!$camiseta) {
                Response::error("Camiseta no encontrada", 404);
            }

            Response::success($camiseta);
        } catch (PDOException $e) {
            Response::error("Error al obtener camiseta: " . $e->getMessage(), 500);
        }
    }

    /**
     * POST /api/camisetas - Crear una nueva camiseta
     */
    public static function store(): void
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            // Validar campos requeridos
            Response::validateRequired($data, ['sku', 'club', 'precio']);

            $db = Database::getConnection();
            $query = "INSERT INTO camisetas (sku, club, precio, precio_oferta, descripcion, activa) 
                     VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($query);

            $activa = $data['activa'] ?? true;
            $stmt->execute([
                $data['sku'],
                $data['club'],
                $data['precio'],
                $data['precio_oferta'] ?? null,
                $data['descripcion'] ?? null,
                $activa ? 1 : 0,
            ]);

            $id = $db->lastInsertId();
            $query = "SELECT * FROM camisetas WHERE id = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$id]);
            $camiseta = $stmt->fetch();

            Response::success($camiseta, 201);
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                Response::error("El SKU ya existe", 400);
            }
            Response::error("Error al crear camiseta: " . $e->getMessage(), 500);
        }
    }

    /**
     * PUT /api/camisetas/{id} - Actualizar una camiseta
     */
    public static function update($id): void
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            $db = Database::getConnection();

            // Verificar que la camiseta existe
            $query = "SELECT * FROM camisetas WHERE id = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$id]);
            $camiseta = $stmt->fetch();

            if (!$camiseta) {
                Response::error("Camiseta no encontrada", 404);
            }

            // Construir update dinámico
            $updates = [];
            $params = [];

            if (isset($data['sku'])) {
                $updates[] = "sku = ?";
                $params[] = $data['sku'];
            }
            if (isset($data['club'])) {
                $updates[] = "club = ?";
                $params[] = $data['club'];
            }
            if (isset($data['precio'])) {
                $updates[] = "precio = ?";
                $params[] = $data['precio'];
            }
            if (isset($data['precio_oferta'])) {
                $updates[] = "precio_oferta = ?";
                $params[] = $data['precio_oferta'];
            }
            if (isset($data['descripcion'])) {
                $updates[] = "descripcion = ?";
                $params[] = $data['descripcion'];
            }
            if (isset($data['activa'])) {
                $updates[] = "activa = ?";
                $params[] = $data['activa'] ? 1 : 0;
            }

            if (empty($updates)) {
                Response::error("No hay campos para actualizar", 400);
            }

            $params[] = $id;
            $query = "UPDATE camisetas SET " . implode(", ", $updates) . " WHERE id = ?";
            $stmt = $db->prepare($query);
            $stmt->execute($params);

            // Retornar camiseta actualizada
            $query = "SELECT * FROM camisetas WHERE id = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$id]);
            $camiseta = $stmt->fetch();

            Response::success($camiseta);
        } catch (PDOException $e) {
            Response::error("Error al actualizar camiseta: " . $e->getMessage(), 500);
        }
    }

    /**
     * DELETE /api/camisetas/{id} - Eliminar una camiseta
     */
    public static function destroy($id): void
    {
        try {
            $db = Database::getConnection();

            // Verificar que la camiseta existe
            $query = "SELECT * FROM camisetas WHERE id = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$id]);
            $camiseta = $stmt->fetch();

            if (!$camiseta) {
                Response::error("Camiseta no encontrada", 404);
            }

            // Eliminar
            $query = "DELETE FROM camisetas WHERE id = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$id]);

            Response::success(['message' => 'Camiseta eliminada correctamente']);
        } catch (PDOException $e) {
            Response::error("Error al eliminar camiseta: " . $e->getMessage(), 500);
        }
    }
}
