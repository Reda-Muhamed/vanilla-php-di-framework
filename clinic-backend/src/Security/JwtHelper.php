<?php

declare(strict_types=1);

namespace Security;

class JwtHelper
{
    private static ?string $secretKey = null;

    // Generate a JWT token from a payload (e.g., user data)
    public static function generate(array $payload): string
    {

        if (self::$secretKey === null) {

            self::$secretKey = $_ENV['JWT_SECRET'];
        }

        // Create the Header (tells the client it's a JWT using HS256 algorithm)
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $base64UrlHeader = self::base64UrlEncode($header);

        // Create the Payload (the actual data, like user_id and expiration time)
        $payload['exp'] = time() + 3600; //  1 hour from now
        $base64UrlPayload = self::base64UrlEncode(json_encode($payload));

        // Create the Signature (The cryptographic lock)
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, self::$secretKey, true);
        $base64UrlSignature = self::base64UrlEncode($signature);

        // Return the final JWT
        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    public static function verify(string $token): ?array
    {
        if (self::$secretKey === null) {

            self::$secretKey = $_ENV['JWT_SECRET'];
        }

        $parts = explode('.', $token);

        if (count($parts) !== 3) {

            return null; // Invalid format
        }

        [$header, $payload, $signature] = $parts;

        // Re-calculate the signature to see if it matches
        $validSignature = self::base64UrlEncode(hash_hmac('sha256', $header . "." . $payload, self::$secretKey, true));

        if (!hash_equals($validSignature, $signature)) {

            return null; // Token was tampered with
        }
        $decodedPayload = json_decode(self::base64UrlDecode($payload), true);

        // Check if the token has expired
        if (isset($decodedPayload['exp']) && $decodedPayload['exp'] < time()) {

            return null; // Token expired
        }
        return $decodedPayload;
    }

    private static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
    private static function base64UrlDecode(string $data): string
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }
}
