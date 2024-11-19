<?php
// Conexão com o banco de dados
$conn = new mysqli('localhost', 'root', '', 'jogopsico');

// Verificar conexão
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Receber os dados via POST
$tipo = $_POST['tipo'];  // 'acerto' ou 'erro'
$id_jogador = $_POST['id']; // ID do jogador

// Validar entrada
if (!in_array($tipo, ['acerto', 'erro']) || !is_numeric($id_jogador)) {
    die("Entrada inválida.");
}

// Atualizar o campo correspondente
$campo = ($tipo == 'acerto') ? 'Acertos' : 'Erros';

$sql = "UPDATE jogadores SET $campo = $campo + 1 WHERE ID = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Erro ao preparar a consulta: " . $conn->error);
}

$stmt->bind_param("i", $id_jogador);

if ($stmt->execute()) {
    echo "Pontuação atualizada com sucesso!";
} else {
    echo "Erro ao atualizar pontuação: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
