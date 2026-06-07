<?php

namespace Config;

class Response
{
    /**
     * Enviar respuesta JSON de éxito
     */
    public static function success($data = null, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');

        $response = [
            'success' => true,
            'data' => $data,
        ];

        echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit();
    }

    /**
     * Enviar respuesta JSON de error
     */
    public static function error(string $message, int $statusCode = 400, $details = null): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');

        $response = [
            'success' => false,
            'error' => $message,
        ];

        if ($details !== null) {
            $response['details'] = $details;
        }

        echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit();
    }

    /**
     * Validar que los campos requeridos estén presentes
     */
    public static function validateRequired(array $data, array $required): bool
    {
        $missing = [];
        foreach ($required as $field) {
            if (!isset($data[$field]) || empty(trim((string)$data[$field]))) {
                $missing[] = $field;
            }
        }

        if (!empty($missing)) {
            self::error("Campos obligatorios faltantes", 400, ['fields' => $missing]);
        }
        return true;
    }
}
