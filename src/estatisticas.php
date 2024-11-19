<?php
// Habilitar a exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conectar ao banco de dados
$conn = new mysqli('localhost', 'root', '', 'jogopsico');

// Verificar conexão
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

// Consultar as estatísticas de todos os jogadores ordenados por ID em ordem crescente
$sql = "SELECT ID, Nome, `Data de Nascimento`, Acertos, Erros FROM jogadores ORDER BY ID DESC";
$result = $conn->query($sql);

// Verificar se a consulta foi bem-sucedida
if (!$result) {
    die("Erro ao executar a consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estatísticas</title>
    <style>
        body {
            background-color: #6a0dad;
            color: #fff;
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        h1 {
            color: #ffd700;
            margin: 20px 0;
        }
        table {
            margin: 20px auto;
            border-collapse: collapse;
            width: 90%;
            max-width: 600px;
            background-color: #501a8b;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #fff;
        }
        th {
            background-color: #ffd700;
            color: #4b0082;
        }
        tr:nth-child(even) {
            background-color: #603bb8;
        }
        button {
            background-color: #ffd700;
            color: #4b0082;
            border: none;
            padding: 10px 20px;
            margin: 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #ffeb3b;
        }
    </style>
</head>
<body>
    <h1>Estatísticas do Jogo</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Data de Nascimento</th>
                <th>Acertos</th>
                <th>Erros</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Exibir os dados dos jogadores
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['ID']}</td>
                            <td>{$row['Nome']}</td>
                            <td>{$row['Data de Nascimento']}</td>
                            <td>{$row['Acertos']}</td>
                            <td>{$row['Erros']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Nenhum jogador encontrado.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <button onclick="window.location.href='home.html'">Voltar para o Início</button>
</body>
</html>

<?php
// Fechar a conexão
$conn->close();
?>
