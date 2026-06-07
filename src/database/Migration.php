<?php

namespace Database;

use Config\Database;
use PDOException;

class Migration
{
    /**
     * Ejecutar todas las migraciones
     */
    public static function runAll(): void
    {
        $migrationsDir = dirname(__DIR__) . '/database/migrations';
        
        if (!is_dir($migrationsDir)) {
            echo "❌ Directorio de migraciones no encontrado: {$migrationsDir}\n";
            return;
        }

        // Obtener archivos SQL ordenados
        $files = glob("{$migrationsDir}/*.sql");
        sort($files);

        if (empty($files)) {
            echo "⚠️  No se encontraron archivos de migración\n";
            return;
        }

        $pdo = Database::getConnection();
        $executed = 0;
        $failed = 0;

        echo "\n🔄 Iniciando migraciones...\n";
        echo str_repeat("─", 60) . "\n";

        foreach ($files as $file) {
            $filename = basename($file);
            
            try {
                // Leer el contenido del archivo SQL
                $sql = file_get_contents($file);
                
                // Dividir por punto y coma para ejecutar cada sentencia
                $statements = array_filter(array_map('trim', explode(';', $sql)));
                
                foreach ($statements as $statement) {
                    if (!empty($statement)) {
                        $pdo->exec($statement);
                    }
                }

                echo "✅ {$filename}\n";
                $executed++;

            } catch (PDOException $e) {
                // Si la tabla ya existe, puede ser un error esperado
                if (strpos($e->getMessage(), 'already exists') !== false) {
                    echo "ℹ️  {$filename} (ya existe)\n";
                    $executed++;
                } else {
                    echo "❌ {$filename}\n";
                    echo "   Error: " . $e->getMessage() . "\n";
                    $failed++;
                }
            } catch (Exception $e) {
                echo "❌ {$filename}\n";
                echo "   Error: " . $e->getMessage() . "\n";
                $failed++;
            }
        }

        echo str_repeat("─", 60) . "\n";
        echo "📊 Resultados: {$executed} exitosa(s), {$failed} fallo(s)\n";

        if ($failed === 0) {
            echo "✅ ¡Migraciones completadas exitosamente!\n";
        }
    }

    /**
     * Ejecutar una migración específica
     */
    public static function run(string $filename): void
    {
        $filepath = dirname(__DIR__) . "/database/migrations/{$filename}";

        if (!file_exists($filepath)) {
            echo "❌ Archivo de migración no encontrado: {$filename}\n";
            return;
        }

        try {
            $sql = file_get_contents($filepath);
            $pdo = Database::getConnection();
            
            $statements = array_filter(array_map('trim', explode(';', $sql)));
            
            foreach ($statements as $statement) {
                if (!empty($statement)) {
                    $pdo->exec($statement);
                }
            }

            echo "✅ Migración ejecutada: {$filename}\n";

        } catch (PDOException $e) {
            echo "❌ Error en migración: " . $e->getMessage() . "\n";
        }
    }

    /**
     * Obtener lista de migraciones
     */
    public static function list(): array
    {
        $migrationsDir = dirname(__DIR__) . '/database/migrations';
        
        if (!is_dir($migrationsDir)) {
            return [];
        }

        $files = glob("{$migrationsDir}/*.sql");
        sort($files);

        return array_map('basename', $files);
    }
}
