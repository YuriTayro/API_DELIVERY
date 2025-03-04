<?php
// Definindo o cabeçalho para JSON
header('Content-Type: application/json');

// Definindo o segredo para o JWT
$secretKey = "chave_secreta_super_segura";

// Carregar as funções de autenticação e delivery
require_once 'auth.php';
require_once 'delivery.php';

// Obter o método e a URI da requisição
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];

// Roteamento da API
if ($requestUri === '/api/auth/token' && $requestMethod === 'POST') {
     error_log("Recebendo requisição para geração de token...");
    // Gerar token JWT
     generateToken();
     exit;
} elseif (strpos($requestUri, '/api/delivery/addresses') !== false) {
    if ($requestMethod === 'GET') {
        // Listar endereços
        return getAddresses($secretKey);
    } elseif ($requestMethod === 'POST') {
        // Criar novo endereço
        return addAddress($secretKey);
    }
} else {
    echo json_encode(['error' => 'Rota não encontrada']);
}
