<?php
session_start();
include('config.php'); // Conexão com o banco de dados

// Verificar se o usuário está logado
if (!isset($_SESSION['username'])) {
    header("Location: indexLogin.php"); // Redireciona para login se não estiver logado
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $COD_COMPRA = $_POST['COD_COMPRA'];
    $QUANTIDADE	= $_POST['QUANTIDADE'];
    $KG	= $_POST['KG'];
    $NOME= $_POST['NOME'];
    $NUMERO_NOTA_FISCAL= $_POST['NUMERO_NOTA_FISCAL'];
    $DATA_COMPRA = $_POST['DATA_COMPRA'];
    $PRECO_COMPRA = $_POST['PRECO_COMPRA'];
    $PRECO_VENDA = $_POST['PRECO_VENDA'];
    $PRECO_TOTAL = $_POST['PRECO_TOTAL'];

    // Preparar e executar a inserção no banco de dados
    $query = "INSERT INTO compras (COD_COMPRA, QUANTIDADE, KG, NOME,NUMERO_NOTA_FISCAL,DATA_COMPRA,PRECO_COMPRA,PRECO_VENDA,PRECO_TOTAL) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iidsisddd', $COD_COMPRA, $QUANTIDADE, $KG, $NOME, $NUMERO_NOTA_FISCAL, $DATA_COMPRA, $PRECO_COMPRA,$PRECO_VENDA, $PRECO_TOTAL); // 'i' para inteiro, 'd' para decimal
    if ($stmt->execute()) {
        echo "Compra registrada com sucesso!";
    } else {
        echo "Erro ao registrar compra: " . $conn->error;
    }
}

// Consultar produtos para exibir no formulário
$query_produto = "SELECT * FROM produto";
$result_produto = $conn->query($query_produto);
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
