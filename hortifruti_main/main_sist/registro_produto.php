<?php
session_start();
include('config.php'); // Conexão com o banco de dados

// Verificar se o usuário está logado
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redireciona para login se não estiver logado
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $qntd_kg = $_POST['qntd_kg'];
    $preco_unitario = $_POST['preco_unitario'];

    // Preparar e executar a inserção no banco de dados
    $query = "INSERT INTO produtos (nome, qntd_kg, preco) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sdd', $nome, $qntd_kg, $preco_unitario);
    if ($stmt->execute()) {
        echo "Produto registrado com sucesso!";
    } else {
        echo "Erro ao registrar produto: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Produto</title>
</head>
<body>
    <header>
        <h1>Registrar Produto</h1>
        <nav>
            <ul>
                <li><a href="dashboard.php">Voltar ao Ínicio</a></li>
            </ul>
        </nav>
    </header>

    <section id="formulario">
        <form method="POST" action="">
            <label for="nome">Nome do Produto</label>
            <input type="text" name="nome" required><br>

            <label for="qntd_kg">Quantidade (kg)</label>
            <input type="number" name="qntd_kg" step="0.01" required><br>

            <label for="preco_unitario">Preço Unitário (por kg)</label>
            <input type="number" name="preco_unitario" step="0.01" required><br>

            <button type="submit">Confirmar Registro</button>
        </form>
    </section>
</body>
</html>


