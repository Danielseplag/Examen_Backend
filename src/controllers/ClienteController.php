<?php

namespace Controllers;

use Config\Response;
use Models\Cliente;
use PDOException;

class ClienteController
{
    /**
     * GET /api/clientes - Listar todos los clientes
     */
    public static function index(): void
    {
        try {
            $clientes = Cliente::all();
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
            $cliente = Cliente::find($id);

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
            Response::validateRequired($data, ['rut', 'nombre_comercial']);

            if (isset($data['categoria']) && !in_array($data['categoria'], ['Regular', 'Preferencial'])) {
                Response::error("La categoría debe ser 'Regular' o 'Preferencial'", 400);
            }

            $id = Cliente::create($data);
            $cliente = Cliente::find($id);

            Response::success($cliente, 201);
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                Response::error("El RUT ya existe", 400);
            }
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

            $cliente = Cliente::find($id);
            if (!$cliente) {
                Response::error("Cliente no encontrado", 404);
            }

            if (isset($data['categoria']) && !in_array($data['categoria'], ['Regular', 'Preferencial'])) {
                Response::error("La categoría debe ser 'Regular' o 'Preferencial'", 400);
            }

            $updated = Cliente::update($id, $data);

            if ($updated === false) {
                Response::error("No hay campos válidos para actualizar", 400);
            }

            $cliente = Cliente::find($id);
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
            $cliente = Cliente::find($id);

            if (!$cliente) {
                Response::error("Cliente no encontrado", 404);
            }

            Cliente::delete($id);
            Response::success(['message' => 'Cliente eliminado correctamente']);
        } catch (PDOException $e) {
            Response::error("Error al eliminar cliente: " . $e->getMessage(), 500);
        }
    }
}
