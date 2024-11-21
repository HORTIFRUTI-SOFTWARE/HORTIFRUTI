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
    $query = "SELECT nome, quantidade, preco FROM produto WHERE nome LIKE ?";
    $stmt = $conn->prepare($query);
    $searchTerm = "%" . $searchTerm . "%"; // Adiciona o '%' para o LIKE funcionar
    $stmt->bind_param('s', $searchTerm);
} else {
    $query = "SELECT nome, quantidade, preco FROM produto";
    $stmt = $conn->prepare($query);
}

$stmt->execute();
$result = $stmt->get_result();

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
    <link rel="stylesheet" href="../src/css/consult.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <header class="card-header">
                <h1><i class="fa-solid fa-boxes"></i> Consulta de Produtos</h1>
                <p>Encontre informações detalhadas sobre os produtos disponíveis.</p>
            </header>
            <form method="POST" action="" class="search-form">
                <input 
                    type="text" 
                    name="search" 
                    id="search" 
                    placeholder="Digite o nome do produto..." 
                    value="<?= htmlspecialchars($searchTerm) ?>" 
                    class="search-input"
                />
                <button type="submit" class="search-btn"><i class="fa-solid fa-search"></i> Buscar</button>
            </form>

            <div class="table-container">
                <table class="table">
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
            </div>
            <a href="dashboard.php" class="back-btn"><i class="fa-solid fa-arrow-left"></i> Voltar ao Início</a>
        </div>
    </div>
</body>
</html>