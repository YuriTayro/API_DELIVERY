<?php
// Dados fictícios para os endereços
$enderecos = [
    ["id" => 1, "rua" => "Rua das Flores", "numero" => "123", "bairro" => "Centro", "cidade" => "São Paulo", "estado" => "SP", "cep" => "01001-000"],
    ["id" => 2, "rua" => "Avenida Paulista", "numero" => "1000", "bairro" => "Bela Vista", "cidade" => "São Paulo", "estado" => "SP", "cep" => "01311-000"]
];

// Função para verificar o token JWT
function checkToken($secretKey) {
    if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
        echo json_encode(["error" => "Token não fornecido"]);
        exit();
    }

    $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
    $matches = [];
    if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        $jwt = $matches[1];
        $decoded = verifyJWT($jwt, $secretKey);

        if ($decoded) {
            return true;
        }
    }
    echo json_encode(["error" => "Token inválido"]);
    exit();
}

// Função para verificar o JWT
function verifyJWT($jwt, $secretKey) {
    try {
        $decoded = JWT::decode($jwt, $secretKey, ['HS256']);
        return $decoded;
    } catch (Exception $e) {
        return null;
    }
}

// Função para listar os endereços
function getAddresses($secretKey) {
    checkToken($secretKey);
    echo json_encode($GLOBALS['enderecos']);
}

// Função para adicionar um novo endereço
function addAddress($secretKey) {
    checkToken($secretKey);

    // Pega os dados do novo endereço
    $input = json_decode(file_get_contents('php://input'), true);

    $novoEndereco = [
        "id" => count($GLOBALS['enderecos']) + 1,
        "rua" => $input['rua'],
        "numero" => $input['numero'],
        "bairro" => $input['bairro'],
        "cidade" => $input['cidade'],
        "estado" => $input['estado'],
        "cep" => $input['cep']
    ];

    // Adiciona o novo endereço à lista
    $GLOBALS['enderecos'][] = $novoEndereco;

    echo json_encode(["message" => "Endereço criado com sucesso", "endereco" => $novoEndereco]);
}
