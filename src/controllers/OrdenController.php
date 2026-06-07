<?php

namespace Controllers;

use Config\Database;
use Config\Response;
use PDOException;

class OrdenController
{
    /**
     * POST /api/ordenes - Crear una nueva orden
     */
    public static function store(): void
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            // Validar campos requeridos
            Response::validateRequired($data, ['cliente_id', 'detalles']);

            if (!is_array($data['detalles']) || empty($data['detalles'])) {
                Response::error("Detalles debe ser un array no vacío", 400);
            }

            $db = Database::getConnection();

            try {
                $db->beginTransaction();

                // Verificar que el cliente existe
                $query = "SELECT * FROM clientes WHERE id = ?";
                $stmt = $db->prepare($query);
                $stmt->execute([$data['cliente_id']]);
                if (!$stmt->fetch()) {
                    throw new PDOException("Cliente no encontrado");
                }

                // Calcular total de la orden
                $total = 0;
                foreach ($data['detalles'] as $detalle) {
                    if (!isset($detalle['camiseta_id'], $detalle['cantidad'])) {
                        throw new PDOException("Cada detalle debe tener camiseta_id y cantidad");
                    }

                    // Obtener precio de la camiseta
                    $query = "SELECT precio, precio_oferta FROM camisetas WHERE id = ?";
                    $stmt = $db->prepare($query);
                    $stmt->execute([$detalle['camiseta_id']]);
                    $camiseta = $stmt->fetch();

                    if (!$camiseta) {
                        throw new PDOException("Camiseta ID {$detalle['camiseta_id']} no encontrada");
                    }

                    $precio = $camiseta['precio_oferta'] ?? $camiseta['precio'];
                    $total += $precio * $detalle['cantidad'];
                }

                // Crear orden
                $query = "INSERT INTO ordenes (cliente_id, total) VALUES (?, ?)";
                $stmt = $db->prepare($query);
                $stmt->execute([$data['cliente_id'], $total]);
                $ordenId = $db->lastInsertId();

                // Crear detalles de orden
                foreach ($data['detalles'] as $detalle) {
                    $query = "SELECT precio, precio_oferta FROM camisetas WHERE id = ?";
                    $stmt = $db->prepare($query);
                    $stmt->execute([$detalle['camiseta_id']]);
                    $camiseta = $stmt->fetch();

                    $precio = $camiseta['precio_oferta'] ?? $camiseta['precio'];

                    $query = "INSERT INTO orden_detalles (orden_id, camiseta_id, cantidad, precio_unitario) 
                             VALUES (?, ?, ?, ?)";
                    $stmt = $db->prepare($query);
                    $stmt->execute([
                        $ordenId,
                        $detalle['camiseta_id'],
                        $detalle['cantidad'],
                        $precio,
                    ]);
                }

                $db->commit();

                // Retornar orden creada con detalles
                $query = "SELECT * FROM ordenes WHERE id = ?";
                $stmt = $db->prepare($query);
                $stmt->execute([$ordenId]);
                $orden = $stmt->fetch();

                $query = "SELECT * FROM orden_detalles WHERE orden_id = ?";
                $stmt = $db->prepare($query);
                $stmt->execute([$ordenId]);
                $detalles = $stmt->fetchAll();

                $orden['detalles'] = $detalles;

                Response::success($orden, 201);
            } catch (PDOException $e) {
                $db->rollBack();
                throw $e;
            }
        } catch (PDOException $e) {
            Response::error("Error al crear orden: " . $e->getMessage(), 500);
        }
    }

    /**
     * GET /api/ordenes/{id} - Obtener detalle de una orden
     */
    public static function show($id): void
    {
        try {
            $db = Database::getConnection();

            // Obtener orden
            $query = "SELECT * FROM ordenes WHERE id = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$id]);
            $orden = $stmt->fetch();

            if (!$orden) {
                Response::error("Orden no encontrada", 404);
            }

            // Obtener detalles de la orden
            $query = "SELECT od.*, c.club, c.descripcion FROM orden_detalles od
                     LEFT JOIN camisetas c ON od.camiseta_id = c.id
                     WHERE od.orden_id = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$id]);
            $detalles = $stmt->fetchAll();

            $orden['detalles'] = $detalles;

            Response::success($orden);
        } catch (PDOException $e) {
            Response::error("Error al obtener orden: " . $e->getMessage(), 500);
        }
    }
}
