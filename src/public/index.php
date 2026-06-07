<?php
/**
 * Punto de entrada de la API TodoCamisetas
 * Todas las peticiones pasan por aquí
 */

// Configurar headers CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=utf-8');

// Si es una petición OPTIONS, responder y salir
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Definir rutas base
define('BASE_PATH', dirname(__DIR__));

// Autoloader simple
require_once BASE_PATH . '/vendor/autoload.php';

// Cargar archivo de rutas
try {
    require_once BASE_PATH . '/routes/router.php';
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Error interno del servidor',
        'message' => $e->getMessage(),
    ]);
}
