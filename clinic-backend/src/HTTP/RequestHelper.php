<?php

declare(strict_types=1);

namespace HTTP;

class RequestHelper
{

    public static function getJsonData(): array
    {
        // Read the raw stream from the request body
        $rawBody = file_get_contents('php://input');
        if (empty($rawBody)) {
            return [];
        }
        // Decode the JSON string into an associative array (true = array, false = object)
        $data = json_decode($rawBody, true);
        // Check for JSON decoding errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Invalid JSON payload: ' . json_last_error_msg());
        }
        return $data;
    }
}