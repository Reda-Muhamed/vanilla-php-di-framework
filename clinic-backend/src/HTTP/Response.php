<?php

declare(strict_types=1);

namespace HTTP;

class Response
{

    public static function json(array $data, int $statusCode = 200): never
    {
        if (ob_get_length()) {

            ob_clean();
        }
        // To make sure that the client knows we're sending JSON not plain test
        header('Content-type: application/json');

        http_response_code($statusCode);

        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        exit;
    }
}
