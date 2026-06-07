<?php

namespace Controllers;

use Config\Database;
use Config\Response;
use PDOException;

class TallaController
{
    /**
     * GET /api/tallas - Listar todas las tallas
     */
    public static function index(): void
    {
        try {
            $db = Database::getConnection();
            $query = "SELECT * FROM tallas ORDER BY id ASC";
            $stmt = $db->prepare($query);
            $stmt->execute();
            $tallas = $stmt->fetchAll();

            Response::success($tallas);
        } catch (PDOException $e) {
            Response::error("Error al obtener tallas: " . $e->getMessage(), 500);
        }
    }

    /**
     * GET /api/tallas/camiseta/{camisetaId} - Obtener tallas disponibles para una camiseta
     */
    public static function findByCamiseta($camisetaId): void
    {
        try {
            $db = Database::getConnection();

            // Verificar que la camiseta existe
            $query = "SELECT * FROM camisetas WHERE id = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$camisetaId]);
            if (!$stmt->fetch()) {
                Response::error("Camiseta no encontrada", 404);
            }

            // Obtener tallas vinculadas a la camiseta
            $query = "SELECT t.* FROM tallas t
                     INNER JOIN camiseta_talla ct ON t.id = ct.talla_id
                     WHERE ct.camiseta_id = ?
                     ORDER BY t.id ASC";
            $stmt = $db->prepare($query);
            $stmt->execute([$camisetaId]);
            $tallas = $stmt->fetchAll();

            Response::success($tallas);
        } catch (PDOException $e) {
            Response::error("Error al obtener tallas: " . $e->getMessage(), 500);
        }
    }

    /**
     * POST /api/camisetas/{camisetaId}/tallas/{tallaId} - Vincular una talla a una camiseta
     */
    public static function attach($camisetaId, $tallaId): void
    {
        try {
            $db = Database::getConnection();

            // Verificar que la camiseta existe
            $query = "SELECT * FROM camisetas WHERE id = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$camisetaId]);
            if (!$stmt->fetch()) {
                Response::error("Camiseta no encontrada", 404);
            }

            // Verificar que la talla existe
            $query = "SELECT * FROM tallas WHERE id = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$tallaId]);
            if (!$stmt->fetch()) {
                Response::error("Talla no encontrada", 404);
            }

            // Verificar si ya existe la relación
            $query = "SELECT * FROM camiseta_talla WHERE camiseta_id = ? AND talla_id = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$camisetaId, $tallaId]);
            if ($stmt->fetch()) {
                Response::error("La talla ya está vinculada a esta camiseta", 400);
            }

            // Crear relación
            $query = "INSERT INTO camiseta_talla (camiseta_id, talla_id) VALUES (?, ?)";
            $stmt = $db->prepare($query);
            $stmt->execute([$camisetaId, $tallaId]);

            Response::success(['message' => 'Talla vinculada correctamente'], 201);
        } catch (PDOException $e) {
            Response::error("Error al vincular talla: " . $e->getMessage(), 500);
        }
    }

    /**
     * DELETE /api/camisetas/{camisetaId}/tallas/{tallaId} - Desvincular una talla de una camiseta
     */
    public static function detach($camisetaId, $tallaId): void
    {
        try {
            $db = Database::getConnection();

            // Verificar que existe la relación
            $query = "SELECT * FROM camiseta_talla WHERE camiseta_id = ? AND talla_id = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$camisetaId, $tallaId]);
            if (!$stmt->fetch()) {
                Response::error("La relación entre camiseta y talla no existe", 404);
            }

            // Eliminar relación
            $query = "DELETE FROM camiseta_talla WHERE camiseta_id = ? AND talla_id = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$camisetaId, $tallaId]);

            Response::success(['message' => 'Talla desvinculada correctamente']);
        } catch (PDOException $e) {
            Response::error("Error al desvincular talla: " . $e->getMessage(), 500);
        }
    }
}
