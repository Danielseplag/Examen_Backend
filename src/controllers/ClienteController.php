<?php

namespace Controllers;

use Config\Database;
use Config\Response;
use PDOException;

class ClienteController
{
    /**
     * GET /api/clientes - Listar todos los clientes
     */
    public static function index(): void
    {
        try {
            $db = Database::getConnection();
            $query = "SELECT * FROM clientes ORDER BY id DESC";
            $stmt = $db->prepare($query);
            $stmt->execute();
            $clientes = $stmt->fetchAll();

            Response::success($clientes);
        } catch (PDOException $e) {
            Response::error("Error al obtener clientes: " . $e->getMessage(), 500);
        }
    }

    /**
     * GET /api/clientes/{id} - Obtener un cliente específico
     */
    public static function show($id): void
    {
        try {
            $db = Database::getConnection();
            $query = "SELECT * FROM clientes WHERE id = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$id]);
            $cliente = $stmt->fetch();

            if (!$cliente) {
                Response::error("Cliente no encontrado", 404);
            }

            Response::success($cliente);
        } catch (PDOException $e) {
            Response::error("Error al obtener cliente: " . $e->getMessage(), 500);
        }
    }

    /**
     * POST /api/clientes - Crear un nuevo cliente
     */
    public static function store(): void
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            // Validar campos requeridos
            Response::validateRequired($data, ['nombre', 'email']);

            $db = Database::getConnection();
            $query = "INSERT INTO clientes (nombre, email, telefono, direccion) 
                     VALUES (?, ?, ?, ?)";
            $stmt = $db->prepare($query);

            $stmt->execute([
                $data['nombre'],
                $data['email'],
                $data['telefono'] ?? null,
                $data['direccion'] ?? null,
            ]);

            $id = $db->lastInsertId();
            $query = "SELECT * FROM clientes WHERE id = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$id]);
            $cliente = $stmt->fetch();

            Response::success($cliente, 201);
        } catch (PDOException $e) {
            Response::error("Error al crear cliente: " . $e->getMessage(), 500);
        }
    }

    /**
     * PUT /api/clientes/{id} - Actualizar un cliente
     */
    public static function update($id): void
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            $db = Database::getConnection();

            // Verificar que el cliente existe
            $query = "SELECT * FROM clientes WHERE id = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$id]);
            $cliente = $stmt->fetch();

            if (!$cliente) {
                Response::error("Cliente no encontrado", 404);
            }

            // Construir update dinámico
            $updates = [];
            $params = [];

            if (isset($data['nombre'])) {
                $updates[] = "nombre = ?";
                $params[] = $data['nombre'];
            }
            if (isset($data['email'])) {
                $updates[] = "email = ?";
                $params[] = $data['email'];
            }
            if (isset($data['telefono'])) {
                $updates[] = "telefono = ?";
                $params[] = $data['telefono'];
            }
            if (isset($data['direccion'])) {
                $updates[] = "direccion = ?";
                $params[] = $data['direccion'];
            }

            if (empty($updates)) {
                Response::error("No hay campos para actualizar", 400);
            }

            $params[] = $id;
            $query = "UPDATE clientes SET " . implode(", ", $updates) . " WHERE id = ?";
            $stmt = $db->prepare($query);
            $stmt->execute($params);

            // Retornar cliente actualizado
            $query = "SELECT * FROM clientes WHERE id = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$id]);
            $cliente = $stmt->fetch();

            Response::success($cliente);
        } catch (PDOException $e) {
            Response::error("Error al actualizar cliente: " . $e->getMessage(), 500);
        }
    }

    /**
     * DELETE /api/clientes/{id} - Eliminar un cliente
     */
    public static function destroy($id): void
    {
        try {
            $db = Database::getConnection();

            // Verificar que el cliente existe
            $query = "SELECT * FROM clientes WHERE id = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$id]);
            $cliente = $stmt->fetch();

            if (!$cliente) {
                Response::error("Cliente no encontrado", 404);
            }

            // Eliminar
            $query = "DELETE FROM clientes WHERE id = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$id]);

            Response::success(['message' => 'Cliente eliminado correctamente']);
        } catch (PDOException $e) {
            Response::error("Error al eliminar cliente: " . $e->getMessage(), 500);
        }
    }
}
