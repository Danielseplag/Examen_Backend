#!/usr/bin/env php
<?php
/**
 * Script de línea de comandos para ejecutar migraciones
 * Uso: php migrate.php [comando]
 * 
 * Comandos:
 *   all        - Ejecutar todas las migraciones
 *   list       - Listar migraciones disponibles
 *   run <file> - Ejecutar una migración específica
 */

// Definir rutas
define('BASE_PATH', __DIR__);

// Cargar autoloader
require_once BASE_PATH . '/vendor/autoload.php';

// Cargar variables de entorno
require_once BASE_PATH . '/config/Database.php';

use Database\Migration;

// Obtener comando
$command = $argv[1] ?? 'all';

switch ($command) {
    case 'all':
        Migration::runAll();
        break;

    case 'list':
        $migrations = Migration::list();
        if (empty($migrations)) {
            echo "No migrations found\n";
        } else {
            echo "Available migrations:\n";
            foreach ($migrations as $migration) {
                echo "  - {$migration}\n";
            }
        }
        break;

    case 'run':
        if (!isset($argv[2])) {
            echo "❌ Debes especificar el nombre del archivo de migración\n";
            echo "Uso: php migrate.php run <filename>\n";
            exit(1);
        }
        Migration::run($argv[2]);
        break;

    default:
        echo "Comando no reconocido: {$command}\n";
        echo "\nUso: php migrate.php [comando]\n";
        echo "Comandos disponibles:\n";
        echo "  all        - Ejecutar todas las migraciones\n";
        echo "  list       - Listar migraciones disponibles\n";
        echo "  run <file> - Ejecutar una migración específica\n";
        exit(1);
}
