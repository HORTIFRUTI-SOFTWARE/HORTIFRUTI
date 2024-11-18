<?php
session_start();
include('config.php'); // Conexão com o banco de dados

// Verificar se o usuário está logado
if (!isset($_SESSION['username'])) {
    header("Location: indexLogin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $COD_PRODUTO = intval($_POST['COD_PRODUTO']);
    $QUANTIDADE = floatval($_POST['QUANTIDADE']);
    $PRECO = floatval($_POST['PRECO']);
    $PRECO_TOTAL = $QUANTIDADE * $PRECO;

    if ($QUANTIDADE > 0 && $PRECO > 0) {
        $query = "INSERT INTO venda (COD_PRODUTO, QUANTIDADE, PRECO, PRECO_TOTAL) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('iidd', $COD_PRODUTO, $QUANTIDADE, $PRECO, $PRECO_TOTAL);
        if ($stmt->execute()) {
            echo "<p>Venda registrada com sucesso!</p>";
        } else {
            echo "<p>Erro ao registrar venda: " . htmlspecialchars($conn->error) . "</p>";
        }
    } else {
        echo "<p>Erro: Quantidade e preço devem ser maiores que zero.</p>";
    }
}

// Consultar produtos para exibir no formulário
$query_produto = "SELECT * FROM produto";
$result_produto = $conn->query($query_produto);

if (!$result_produto) {
    die("Erro na consulta de produtos: " . $conn->error);
}

// Consultar o total das vendas
$query_PRECO_TOTAL = "SELECT SUM(PRECO_TOTAL) AS PRECO_TOTAL FROM venda";
$result_PRECO_TOTAL = $conn->query($query_PRECO_TOTAL);
$PRECO_TOTAL = $result_PRECO_TOTAL ? $result_PRECO_TOTAL->fetch_assoc()['PRECO_TOTAL'] : 0;
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
            </ul>
        </nav>
    </header>

    <section id="formulario">
        <form method="POST" action="">
            <label for="COD_PRODUTO">Produto</label>
            <select name="COD_PRODUTO" required>
                <?php if ($result_produto->num_rows > 0) {
                    while ($row = $result_produto->fetch_assoc()) { ?>
                        <option value="<?php echo htmlspecialchars($row['COD_PRODUTO']); ?>">
                            <?php echo htmlspecialchars($row['NOME']); // Alterar 'nome' para a coluna correta, como 'descricao' ?>
                        </option>
                <?php }
                } else { ?>
                    <option value="">Nenhum produto encontrado</option>
                <?php } ?>
            </select><br>

            <label for="quantidade">Quantidade (kg)</label>
            <input type="number" name="QUANTIDADE" step="0.01" required><br>

            <label for="preco">Preço Unitário (por kg)</label>
            <input type="number" name="PRECO" step="0.01" required><br>

            <button type="submit">Registrar Venda</button>
        </form>
    </section>

    <section id="PRECO_TOTAL">
        <h2>Total de Vendas Registradas</h2>
        <p>Valor total de todas as vendas: R$ <?php echo number_format($PRECO_TOTAL, 2, ',', '.'); ?></p>
    </section>
</body>
</html>
