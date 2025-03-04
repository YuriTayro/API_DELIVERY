<?php
// JWT.php - Função para manipulação de JWT
class JWT {

    public static function encode($payload, $key) {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode($payload);

        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        $signature = hash_hmac('sha256', "$base64UrlHeader.$base64UrlPayload", $key, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        return "$base64UrlHeader.$base64UrlPayload.$base64UrlSignature";
    }

    public static function decode($jwt, $key) {
        list($headerEncoded, $payloadEncoded, $signatureEncoded) = explode('.', $jwt);

        $header = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $headerEncoded)), true);
        $payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $payloadEncoded)), true);

        return $payload;
    }
}

