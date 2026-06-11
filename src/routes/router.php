<?php
/**
 * Router de la API
 * Maneja las rutas mediante expresiones regulares
 */

use Config\Response;
use Controllers\CamisetaController;
use Controllers\ClienteController;
use Controllers\TallaController;
use Controllers\OrdenController;

// Obtener método y URI
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Eliminar la base path si existe
$basePath = '';
if (strpos($uri, '/todo-camisetas') === 0) {
    $uri = substr($uri, strlen('/todo-camisetas'));
}

// Si la URI está vacía, establecerla como /
if (empty($uri)) {
    $uri = '/';
}

// Variable para rastrear si se encontró una ruta
$routeFound = false;

// ════════════════════════════════════════════════════════════
// RUTAS: CAMISETAS
// ════════════════════════════════════════════════════════════

// GET /api/camisetas - Listar todas las camisetas
if ($method === 'GET' && preg_match('#^/api/camisetas/?$#', $uri)) {
    CamisetaController::index();
    $routeFound = true;
}

// GET /api/camisetas/{id}/precio-final - Obtener precio final
if ($method === 'GET' && preg_match('#^/api/camisetas/(\d+)/precio-final/?$#', $uri, $matches)) {
    CamisetaController::getPrecioFinal($matches[1]);
    $routeFound = true;
}

// GET /api/camisetas/{id} - Obtener una camiseta
if ($method === 'GET' && preg_match('#^/api/camisetas/(\d+)/?$#', $uri, $matches)) {
    CamisetaController::show($matches[1]);
    $routeFound = true;
}

// POST /api/camisetas - Crear camiseta
if ($method === 'POST' && preg_match('#^/api/camisetas/?$#', $uri)) {
    CamisetaController::store();
    $routeFound = true;
}

// PUT /api/camisetas/{id} - Actualizar camiseta
if ($method === 'PUT' && preg_match('#^/api/camisetas/(\d+)/?$#', $uri, $matches)) {
    CamisetaController::update($matches[1]);
    $routeFound = true;
}

// DELETE /api/camisetas/{id} - Eliminar camiseta
if ($method === 'DELETE' && preg_match('#^/api/camisetas/(\d+)/?$#', $uri, $matches)) {
    CamisetaController::destroy($matches[1]);
    $routeFound = true;
}

// ════════════════════════════════════════════════════════════
// RUTAS: CLIENTES
// ════════════════════════════════════════════════════════════

// GET /api/clientes - Listar todos los clientes
if ($method === 'GET' && preg_match('#^/api/clientes/?$#', $uri)) {
    ClienteController::index();
    $routeFound = true;
}

// GET /api/clientes/{id} - Obtener un cliente
if ($method === 'GET' && preg_match('#^/api/clientes/(\d+)/?$#', $uri, $matches)) {
    ClienteController::show($matches[1]);
    $routeFound = true;
}

// POST /api/clientes - Crear cliente
if ($method === 'POST' && preg_match('#^/api/clientes/?$#', $uri)) {
    ClienteController::store();
    $routeFound = true;
}

// PUT /api/clientes/{id} - Actualizar cliente
if ($method === 'PUT' && preg_match('#^/api/clientes/(\d+)/?$#', $uri, $matches)) {
    ClienteController::update($matches[1]);
    $routeFound = true;
}

// DELETE /api/clientes/{id} - Eliminar cliente
if ($method === 'DELETE' && preg_match('#^/api/clientes/(\d+)/?$#', $uri, $matches)) {
    ClienteController::destroy($matches[1]);
    $routeFound = true;
}

// ════════════════════════════════════════════════════════════
// RUTAS: TALLAS
// ════════════════════════════════════════════════════════════

// GET /api/tallas - Listar todas las tallas
if ($method === 'GET' && preg_match('#^/api/tallas/?$#', $uri)) {
    TallaController::index();
    $routeFound = true;
}

// GET /api/tallas/camiseta/{camisetaId} - Obtener tallas de una camiseta
if ($method === 'GET' && preg_match('#^/api/tallas/camiseta/(\d+)/?$#', $uri, $matches)) {
    TallaController::findByCamiseta($matches[1]);
    $routeFound = true;
}

// POST /api/camisetas/{camisetaId}/tallas/{tallaId} - Vincular talla a camiseta
if ($method === 'POST' && preg_match('#^/api/camisetas/(\d+)/tallas/(\d+)/?$#', $uri, $matches)) {
    TallaController::attach($matches[1], $matches[2]);
    $routeFound = true;
}

// DELETE /api/camisetas/{camisetaId}/tallas/{tallaId} - Desvincular talla de camiseta
if ($method === 'DELETE' && preg_match('#^/api/camisetas/(\d+)/tallas/(\d+)/?$#', $uri, $matches)) {
    TallaController::detach($matches[1], $matches[2]);
    $routeFound = true;
}

// ════════════════════════════════════════════════════════════
// RUTAS: ÓRDENES (VENTAS)
// ════════════════════════════════════════════════════════════

// POST /api/ordenes - Crear una nueva orden (Procesar Venta)
if ($method === 'POST' && preg_match('#^/api/ordenes/?$#', $uri)) {
    OrdenController::store();
    $routeFound = true;
}

// GET /api/ordenes/{id} - Ver detalle de una orden
if ($method === 'GET' && preg_match('#^/api/ordenes/(\d+)/?$#', $uri, $matches)) {
    OrdenController::show($matches[1]);
    $routeFound = true;
}

// ════════════════════════════════════════════════════════════
// SALUD - Status check
// ════════════════════════════════════════════════════════════

if ($method === 'GET' && preg_match('#^/api/health/?$#', $uri)) {
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'status' => 'API running',
        'timestamp' => date('Y-m-d H:i:s'),
    ]);
    exit();
}

// ════════════════════════════════════════════════════════════
// RUTA NO ENCONTRADA
// ════════════════════════════════════════════════════════════

if (!$routeFound) {
    Response::error("Ruta no encontrada: {$method} {$uri}", 404);
}
