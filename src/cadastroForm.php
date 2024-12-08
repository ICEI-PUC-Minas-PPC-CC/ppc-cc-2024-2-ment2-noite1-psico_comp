<?php
header('Content-Type: application/json');

// Dados do banco de dados
$host = 'localhost';
$user = 'root'; 
$password = ''; 
$dbname = 'psico';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Erro de conexão com o banco de dados."]);
    exit;
}

// Receber dados do formulário
$paciente = $_POST['Paciente'] ?? '';
$psicologa = $_POST['Psicologa'] ?? '';
$data_sessao = $_POST['Data_da_sessao'] ?? '';

if (empty($paciente) || empty($psicologa) || empty($data_sessao)) {
    echo json_encode(["success" => false, "message" => "Todos os campos são obrigatórios."]);
    exit;
}

// Inserir no banco de dados
$stmt = $conn->prepare("INSERT INTO estatisticas (Paciente, Psicologa, `Data da sessão`) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $paciente, $psicologa, $data_sessao);

if ($stmt->execute()) {
    // Retornar o ID do último registro inserido
    echo json_encode(["success" => true, "id" => $conn->insert_id]);
} else {
    echo json_encode(["success" => false, "message" => "Erro ao salvar os dados no banco de dados."]);
}

$stmt->close();
$conn->close();
?>
