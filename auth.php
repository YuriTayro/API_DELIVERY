<?php
// Usar a biblioteca JWT (necessário incluir o arquivo JWT.php ou código abaixo)
require_once 'JWT.php'; // Aqui você pode incluir ou substituir por seu código JWT

// Função para gerar o token
function generateToken() {
    // Pega o corpo da requisição
    $input = json_decode(file_get_contents('php://input'), true);

    // Logando os dados recebidos
    error_log("Dados recebidos: " . print_r($input, true));

    // Credenciais para teste
    $login = 'admin@delivery.com';
    $password = 'admin123'; // Senha simples sem hash

    $inputLogin = $input['login'] ?? '';
    $inputPassword = $input['password'] ?? '';

    // Logando as credenciais recebidas
    error_log("Login recebido: " . $inputLogin);
    error_log("Senha recebida: " . $inputPassword);

    // Verifica as credenciais
    if ($inputLogin === $login && $inputPassword === $password) {
        // Cria o token
        $token = createToken($inputLogin, $GLOBALS['secretKey']);

        // Logando o token gerado
        error_log("Token gerado: " . $token);

        echo json_encode(["token" => $token]);
    } else {
        error_log("Erro: Credenciais inválidas");
        echo json_encode(["error" => "Credenciais inválidas"]);
    }
}

// Função para criar o token JWT
function createToken($login, $secretKey) {
    $issuedAt = time();
    $expirationTime = $issuedAt + 3600; // Token expira em 1 hora
    $payload = [
        'iat' => $issuedAt,
        'exp' => $expirationTime,
        'login' => $login
    ];

     $token = JWT::encode($payload, $secretKey);

    // Logando o token gerado
    error_log("Token criado para $login: " . $token);

    return $token;
}

