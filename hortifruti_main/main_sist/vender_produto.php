<?php
session_start();
include('config.php'); // Conexão com o banco de dados

// Verificar se o usuário está logado
if (!isset($_SESSION['username'])) {
    header("Location: indexLogin.php"); // Redireciona para login se não estiver logado
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $COD_VENDA = $_POST['COD_VENDA'];
    $DATA_VENDA= $_POST['DATA_COMPRA'];
    $QUANTIDADE	= $_POST['QUANTIDADE'];
    $PRECO_TOTAL = $_POST['PRECO_TOTAL'];
    $KG	= $_POST['KG'];
    $COD_PRODUTO= $_POST['COD_PRODUTO'];
    

    // Preparar e executar a inserção no banco de dados
    $query = "INSERT INTO vendas (COD_VENDA, DATA_COMPRA, QUANTIDADE, PRECO_TOTAL,KG,COD_PRODUTO) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iddd', $COD_VENDA, $DATA_COMPRA, $QUANTIDADE, $PRECO_TOTAL,$KG,$COD_PRODUTO); // 'i' para inteiro, 'd' para decimal
    if ($stmt->execute()) {
        echo "Venda registrada com sucesso!";
    } else {
        echo "Erro ao registrar venda: " . $conn->error;
    }
}

// Consultar produtos para exibir no formulário
$query_produto = "SELECT * FROM produto";
$result_produto = $conn->query($query_produto);

// Consultar o total das vendas
$query_PRECO_TOTAL = "SELECT SUM(PRECO_TOTAL) AS PRECO_TOTAL FROM venda";
$result_PRECO_TOTAL = $conn->query($query_PRECO_TOTAL);
$PRECO_TOTAL = $result_PRECO_TOTAL->fetch_assoc()['PRECO_TOTAL'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Venda</title>
</head>
<body>
    <header>
        <h1>Registrar Venda de Produto</h1>
        <nav>
            <ul>
                <li><a href="dashboard.php">Voltar ao Ínicio</a></li>
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
            <input type="number" name="KG" step="0.01" required><br>

            <label for="preco_unitario">Preço Unitário (por kg)</label>
            <input type="number" name="preco_unitario" step="0.01" required><br>

            <button type="submit">Registrar Venda</button>
        </form>
    </section>

    <section id="PRECO_TOTAL">
        <h2>Total de Vendas Registradas</h2>
        <p>Valor total de todas as vendas: R$ <?php echo number_format($PRECO_TOTAL, 2, ',', '.'); ?></p>
    </section>
</body>
</html>
