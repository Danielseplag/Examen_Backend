<?php

namespace Controllers;

use Config\Response;
use Models\Camiseta;
use Models\Cliente;
use PDOException;

class CamisetaController
{
    /**
     * GET /api/camisetas - Listar todas las camisetas
     */
    public static function index(): void
    {
        try {
            $camisetas = Camiseta::all();
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
            $camiseta = Camiseta::find($id);

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
            Response::validateRequired($data, ['sku', 'titulo', 'club', 'pais', 'tipo', 'color', 'precio']);

            $tiposValidos = ['Local', 'Visita', '3era Camiseta', 'Femenino Local', 'Niño'];
            if (!in_array($data['tipo'], $tiposValidos)) {
                Response::error("El tipo de camiseta no es válido", 400);
            }

            $id = Camiseta::create($data);
            $camiseta = Camiseta::find($id);

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

            $camiseta = Camiseta::find($id);
            if (!$camiseta) {
                Response::error("Camiseta no encontrada", 404);
            }

            if (isset($data['tipo'])) {
                $tiposValidos = ['Local', 'Visita', '3era Camiseta', 'Femenino Local', 'Niño'];
                if (!in_array($data['tipo'], $tiposValidos)) {
                    Response::error("El tipo de camiseta no es válido", 400);
                }
            }

            $updated = Camiseta::update($id, $data);

            if ($updated === false) {
                Response::error("No hay campos válidos para actualizar", 400);
            }

            $camiseta = Camiseta::find($id);
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
            $camiseta = Camiseta::find($id);

            if (!$camiseta) {
                Response::error("Camiseta no encontrada", 404);
            }

            Camiseta::delete($id);
            Response::success(['message' => 'Camiseta eliminada correctamente']);
        } catch (PDOException $e) {
            Response::error("Error al eliminar camiseta: " . $e->getMessage(), 500);
        }
    }

    /**
     * GET /api/camisetas/{id}/precio-final?cliente_id=X - Obtener precio con reglas de negocio
     */
    public static function getPrecioFinal($id): void
    {
        try {
            $clienteId = $_GET['cliente_id'] ?? null;
            if (!$clienteId) {
                Response::error("El parámetro cliente_id es requerido", 400);
            }

            $camiseta = Camiseta::find($id);
            if (!$camiseta) {
                Response::error("Camiseta no encontrada", 404);
            }

            $cliente = Cliente::find($clienteId);
            if (!$cliente) {
                Response::error("Cliente no encontrado", 404);
            }

            $precioFinal = $camiseta['precio'];
            $motivo = "Precio base aplicado";

            if ($cliente['categoria'] === 'Preferencial' && !is_null($camiseta['precio_oferta'])) {
                $precioFinal = $camiseta['precio_oferta'];
                $motivo = "Precio de oferta aplicado (cliente Preferencial)";
            }

            Response::success([
                'camiseta_id' => $camiseta['id'],
                'titulo' => $camiseta['titulo'],
                'precio' => $camiseta['precio'],
                'precio_oferta' => $camiseta['precio_oferta'],
                'cliente_id' => $cliente['id'],
                'cliente_categoria' => $cliente['categoria'],
                'descuento_cliente' => $cliente['descuento_porcentaje'],
                'precio_final' => $precioFinal,
                'motivo' => $motivo
            ]);
        } catch (PDOException $e) {
            Response::error("Error al calcular precio final: " . $e->getMessage(), 500);
        }
    }
}
