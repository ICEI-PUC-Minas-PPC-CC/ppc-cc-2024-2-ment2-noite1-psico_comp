<?php
// Conexão com o banco de dados
$conn = new mysqli('localhost', 'root', '', 'psico');

// Verificar conexão
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

$mensagem = "";

// Receber a requisição de salvar anotações
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_jogador'])) {
    $id_jogador = $_POST['id_jogador'];
    $anotacoes = $_POST['anotacoes'];

    // Atualizar as anotações no banco de dados
    $sql = "UPDATE estatisticas SET Anotações = ? WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $anotacoes, $id_jogador);

    if ($stmt->execute()) {
        $mensagem = "Anotações salvas com sucesso!";
    } else {
        $mensagem = "Erro ao salvar anotações.";
    }

    $stmt->close();
}

// Consultar os dados de pontuação (acertos, erros e pulos) dos jogadores
$sql = "SELECT ID, Paciente, Psicologa, Acertos, Erros, Pulos, Anotações FROM estatisticas";
$result = $conn->query($sql);

// Fechar a conexão com o banco de dados
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estatísticas do Jogo</title>
    <style>
        body {
            background-color: #6a0dad;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: 'Comic Sans MS', cursive, sans-serif;
        }

        .container {
            background-color: #501a8b;
            border-radius: 15px;
            padding: 30px;
            width: 90%;
            max-width: 800px;
            text-align: center;
        }

        h1 {
            color: #ffd700;
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ffd700;
        }

        th {
            background-color: #ffd700;
            color: #4b0082;
        }

        td {
            background-color: #f4f4f4;
            color: #000;
        }

        textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            border: 1px solid #ffd700;
        }

        button {
            background-color: #ffd700;
            color: #4b0082;
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            margin-top: 20px;
        }

        .update-btn {
            background-color: #4b0082;
            color: #fff;
        }

        .alert {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #4b0082;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            font-size: 16px;
            display: none;
            z-index: 1000;
        }
    </style>
    <script>
        function mostrarMensagem(mensagem) {
            const alertBox = document.querySelector('.alert');
            alertBox.textContent = mensagem;
            alertBox.style.display = 'block';
            setTimeout(() => {
                alertBox.style.display = 'none';
            }, 3000);
        }

        // Exibir mensagem PHP no carregamento, se existir
        window.onload = () => {
            <?php if (!empty($mensagem)): ?>
                mostrarMensagem("<?php echo $mensagem; ?>");
            <?php endif; ?>
        };
    </script>
</head>
<body>
    <div class="alert"></div>
    <div class="container">
        <h1>Estatísticas do Jogo</h1>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Paciente</th>
                        <th>Psicóloga</th>
                        <th>Acertos</th>
                        <th>Erros</th>
                        <th>Pulos</th>
                        <th>Anotações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['ID']; ?></td>
                            <td><?php echo $row['Paciente']; ?></td>
                            <td><?php echo $row['Psicologa']; ?></td>
                            <td><?php echo $row['Acertos']; ?></td>
                            <td><?php echo $row['Erros']; ?></td>
                            <td><?php echo $row['Pulos']; ?></td>
                            <td>
                                <form method="POST" action="estatisticas.php">
                                    <textarea name="anotacoes"><?php echo htmlspecialchars($row['Anotações']); ?></textarea>
                                    <input type="hidden" name="id_jogador" value="<?php echo $row['ID']; ?>">
                                    <button type="submit" class="update-btn">Salvar Anotações</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhum dado encontrado.</p>
        <?php endif; ?>

        <button onclick="window.location.href='home.html'">Voltar para o Início</button>
    </div>
</body>
</html>
