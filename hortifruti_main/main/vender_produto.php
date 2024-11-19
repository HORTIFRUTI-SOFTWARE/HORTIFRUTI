<?php
session_start();
include('config.php'); // Conexão com o banco de dados

// Verificar se o usuário está logado
if (!isset($_SESSION['username'])) {
    header("Location: indexLogin.php");
    exit();
}

$message = ''; // Variável para armazenar mensagens de sucesso ou erro

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
            $message = "<p class='success'>Venda registrada com sucesso!</p>";
        } else {
            $message = "<p class='error'>Erro ao registrar venda: " . htmlspecialchars($conn->error) . "</p>";
        }
    } else {
        $message = "<p class='error'>Erro: Quantidade e preço devem ser maiores que zero.</p>";
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
    <title>Registro de Vendas - Interface Premium</title>
    <link rel="stylesheet" href="..\src\css\venda.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <h1><i class="fas fa-cash-register"></i> Registro de Vendas</h1>
            <nav>
                <a href="dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i> Voltar ao Início</a>
            </nav>
        </header>

        <section id="formulario" class="card">
            <div class="message-container"><?php echo $message; ?></div>
            <form method="POST" action="" class="form">
                <div class="input-group">
                    <label for="COD_PRODUTO">Produto</label>
                    <select name="COD_PRODUTO" required>
                        <?php while ($row = $result_produto->fetch_assoc()) { ?>
                            <option value="<?php echo htmlspecialchars($row['COD_PRODUTO']); ?>">
                                <?php echo htmlspecialchars($row['NOME']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="input-group">
                    <label for="quantidade">Quantidade (kg)</label>
                    <input type="number" name="QUANTIDADE" step="0.01" placeholder="Ex: 2.5" required>
                </div>

                <div class="input-group">
                    <label for="preco">Preço Unitário (por kg)</label>
                    <input type="number" name="PRECO" step="0.01" placeholder="Ex: 20.00" required>
                </div>

                <button type="submit" class="btn"><i class="fas fa-save"></i> Registrar Venda</button>
            </form>
        </section>

        <section id="PRECO_TOTAL" class="card summary">
            <h2><i class="fas fa-chart-line"></i> Total de Vendas</h2>
            <p>Valor total de todas as vendas: <strong>R$ <?php echo number_format($PRECO_TOTAL, 2, ',', '.'); ?></strong></p>
        </section>
    </div>
</body>
</html>