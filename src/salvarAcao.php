<?php
// Conexão com o banco de dados
$conn = new mysqli('localhost', 'root', '', 'psico');

// Verificar conexão
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Receber os dados via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo'];  // 'acerto', 'erro', ou 'pulo'
    $id_jogador = $_POST['id']; // ID do jogador

    // Validar entrada
    if (!in_array($tipo, ['acerto', 'erro', 'pulo']) || !is_numeric($id_jogador)) {
        die("Entrada inválida.");
    }

    // Determinar o campo correto para atualização
    $campo = '';
    if ($tipo === 'acerto') {
        $campo = 'Acertos';
    } elseif ($tipo === 'erro') {
        $campo = 'Erros';
    } elseif ($tipo === 'pulo') {
        $campo = 'Pulos';
    }

    // Atualizar o campo correspondente no banco de dados
    $sql = "UPDATE estatisticas SET $campo = $campo + 1 WHERE ID = $id_jogador";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Erro ao preparar a consulta: " . $conn->error);
    }

    // $stmt->bind_param("i", $id_jogador);

    if ($stmt->execute()) {
        echo "Pontuação atualizada com sucesso!";
    } else {
        echo "Erro ao atualizar pontuação: " . $stmt->error;
    }

    $stmt->close();
}

// Fechar a conexão com o banco de dados
$conn->close();
?>
