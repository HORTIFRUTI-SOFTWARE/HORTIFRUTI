<?php
session_start();
include('config.php'); // Conexão com o banco de dados

// Verificar se o usuário está logado
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redireciona para login se não estiver logado
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_produto = $_POST['id_produto'];
    $qntd_kg = $_POST['qntd_kg'];
    $preco_unitario = $_POST['preco_unitario'];
    $total = $qntd_kg * $preco_unitario;

    // Preparar e executar a inserção no banco de dados
    $query = "INSERT INTO compras (id_produto, qntd_kg, preco_unitario, total) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iddd', $id_produto, $qntd_kg, $preco_unitario, $total); // 'i' para inteiro, 'd' para decimal
    if ($stmt->execute()) {
        echo "Compra registrada com sucesso!";
    } else {
        echo "Erro ao registrar compra: " . $conn->error;
    }
}

// Consultar produtos para exibir no formulário
$query_produtos = "SELECT * FROM produtos";
$result_produtos = $conn->query($query_produtos);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Compra</title>
</head>
<body>
    <header>
        <h1>Registrar Compra de Produto</h1>
        <nav>
            <ul>
                <li><a href="dashboard.php">Voltar ao Ínicio</a></li>
            </ul>
        </nav>
    </header>

    <section id="formulario">
        <form method="POST" action="">
            <label for="id_produto">Produto</label>
            <select name="id_produto" required>
                <?php while ($row = $result_produtos->fetch_assoc()) { ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['nome']; ?></option>
                <?php } ?>
            </select><br>

            <label for="qntd_kg">Quantidade (kg)</label>
            <input type="number" name="qntd_kg" step="0.01" required><br>

            <label for="preco_unitario">Preço Unitário (por kg)</label>
            <input type="number" name="preco_unitario" step="0.01" required><br>

            <button type="submit">Registrar Compra</button>
        </form>
    </section>
</body>
</html>
