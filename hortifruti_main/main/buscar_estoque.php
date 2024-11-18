<?php
session_start();
include('config.php'); // Conexão com o banco de dados

// Verificar se o usuário está logado
if (!isset($_SESSION['username'])) {
    header("Location: indexLogin.php"); // Redireciona para login se não estiver logado
    exit();
}

// Variável para armazenar o termo de pesquisa (caso exista)
$searchTerm = isset($_POST['search']) ? trim($_POST['search']) : '';

// Preparar a consulta para buscar os produtos
if (!empty($searchTerm)) {
    // Caso haja termo de pesquisa, filtra os produtos pelo nome
    $query = "SELECT nome, quantidade, preco FROM produto WHERE nome LIKE ?";
    $stmt = $conn->prepare($query);
    $searchTerm = "%" . $searchTerm . "%"; // Adiciona o '%' para o LIKE funcionar
    $stmt->bind_param('s', $searchTerm);
} else {
    // Caso não haja pesquisa, retorna todos os produtos
    $query = "SELECT nome, quantidade, preco FROM produto";
    $stmt = $conn->prepare($query);
}

$stmt->execute();
$result = $stmt->get_result();

// Armazenar os resultados
$produtos = [];
if ($result->num_rows > 0) {
    $produtos = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Produtos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 10px 0;
            text-align: center;
        }
        form {
            max-width: 500px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background: #f9f9f9;
        }
        label, input, button {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }
        button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }
        th {
            background-color: #333;
            color: white;
        }
        td {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <header>
        <h1>Consulta de Produtos</h1>
        <nav>
            <a href="dashboard.php" style="color: white;">Voltar ao Início</a>
        </nav>
    </header>

    <section id="consulta">
        <form method="POST" action="">
            <label for="search">Buscar por nome do produto</label>
            <input type="text" name="search" id="search" placeholder="Digite o nome do produto" value="<?= htmlspecialchars($searchTerm) ?>">
            <button type="submit">Buscar</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Quantidade (kg)</th>
                    <th>Preço (por kg)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($produtos) > 0): ?>
                    <?php foreach ($produtos as $produto): ?>
                        <tr>
                            <td><?= htmlspecialchars($produto['nome']) ?></td>
                            <td><?= htmlspecialchars($produto['quantidade']) ?></td>
                            <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">Nenhum produto encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</body>
</html>