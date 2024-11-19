<?php
// Habilitar a exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conexão com o banco de dados
$conn = new mysqli('localhost', 'root', '', 'jogopsico');

// Verificar conexão
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Receber os dados do formulário
$nome = $_POST['nome'];
$data_nascimento = $_POST['data_nascimento'];

// Usando prepared statement para evitar SQL Injection
$stmt = $conn->prepare("INSERT INTO jogadores (`Nome`, `Data de Nascimento`, `Acertos`, `Erros`) VALUES (?, ?, 0, 0)");

if ($stmt === false) {
    die(json_encode(['status' => 'erro', 'message' => 'Erro ao preparar a consulta: ' . $conn->error]));
}

$stmt->bind_param("ss", $nome, $data_nascimento);

// Executar a inserção no banco de dados
if ($stmt->execute()) {
    // Retorna o ID do jogador cadastrado
    echo json_encode(['status' => 'sucesso', 'id' => $stmt->insert_id]);
} else {
    echo json_encode(['status' => 'erro', 'message' => $stmt->error]);
}

// Fechar a conexão
$stmt->close();
$conn->close();
?>
