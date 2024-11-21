<?php
session_start();
include('config.php');

// Variáveis de mensagem
$mensagem = "";
$VALOR_TOTAL = 0;

if (!isset($_SESSION['username'])) {
    header("Location: indexLogin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['COD_PRODUTO']) && isset($_POST['QUANTIDADE'])) {
        $id_produto = $_POST['COD_PRODUTO'];
        $quantidade_perdida = floatval($_POST['QUANTIDADE']);

        if ($quantidade_perdida > 0) {
            $queryProduto = "SELECT preco, nome FROM produto WHERE COD_PRODUTO = ?";
            $stmtProduto = $conn->prepare($queryProduto);
            $stmtProduto->bind_param('i', $id_produto);
            $stmtProduto->execute();
            $stmtProduto->bind_result($preco, $nomeProduto);
            $stmtProduto->fetch();
            $stmtProduto->close();

            $VALOR_TOTAL = $quantidade_perdida * $preco;

            $query = "INSERT INTO PERCA (COD_PRODUTO, QUANTIDADE, VALOR_TOTAL, DATA_PERCA) VALUES (?, ?, ?, NOW())";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('idd', $id_produto, $quantidade_perdida, $VALOR_TOTAL);

            if ($stmt->execute()) {
                $updateQuery = "UPDATE produto SET quantidade = quantidade - ? WHERE COD_PRODUTO = ?";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bind_param('di', $quantidade_perdida, $id_produto);
                $updateStmt->execute();
                $mensagem = "Perda registrada com sucesso!";
            } else {
                $mensagem = "Erro ao registrar a perda: " . $conn->error;
            }
        } else {
            $mensagem = "Por favor, preencha a quantidade perdida corretamente.";
        }
    } else {
        $mensagem = "Erro: Dados de produto ou quantidade não enviados.";
    }
}

$produtos = [];
if (isset($_GET['query']) && !empty($_GET['query'])) {
    $searchQuery = '%' . $_GET['query'] . '%';
    $stmtSearch = $conn->prepare("SELECT COD_PRODUTO, nome, preco FROM produto WHERE nome LIKE ?");
    $stmtSearch->bind_param('s', $searchQuery);
    $stmtSearch->execute();
    $resultSearch = $stmtSearch->get_result();
    while ($row = $resultSearch->fetch_assoc()) {
        $produtos[] = $row;
    }
    $stmtSearch->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Perda</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="..\src\css\perda.css">
</head>
<body>
    <header>
        <h1>Registrar Perda</h1>
    </header>
    <div class="container">
        <form method="POST" action="">
            <label for="produto">
                <i class="fas fa-search"></i> Pesquisar Produto
            </label>
            <input type="text" id="produto" name="produto" placeholder="Nome do produto..." onkeyup="buscarProduto()" required>
            <div class="sla" id="produto-lista">
                <?php foreach ($produtos as $produto): ?>
                    <div class="sla" onclick="selecionarProduto(<?= $produto['COD_PRODUTO'] ?>, '<?= addslashes($produto['nome']) ?>', <?= $produto['preco'] ?>)">
                        <?= $produto['nome'] ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <input type="hidden" id="id_produto" name="COD_PRODUTO">
            <input type="hidden" id="preco_produto">
            <label for="quantidade_perdida">Quantidade Perdida (kg)</label>
            <input type="number" id="quantidade_perdida" name="QUANTIDADE" step="0.01" required oninput="calcularTotal()">
            <button type="submit">
                <i class="fas fa-check"></i> Registrar
            </button>
        </form>
        <?php if ($mensagem): ?>
            <div class="mensagem"><?= $mensagem ?></div>
        <?php endif; ?>
        <div class="total-perda">
            <h3>Total Perdido</h3>
            <p id="total-perdido">R$ 0,00</p>
        </div>
        <div class="back-button">
            <a href="dashboard.php">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <script>
        function buscarProduto() {
            var query = document.getElementById("produto").value;
            if (query.length > 2) {
                window.location.href = "?query=" + query;
            }
        }

        function selecionarProduto(id, nome, preco) {
            document.getElementById("produto").value = nome;
            document.getElementById("id_produto").value = id;
            document.getElementById("preco_produto").value = preco;
            calcularTotal();
        }

        function calcularTotal() {
            var quantidade = parseFloat(document.getElementById("quantidade_perdida").value);
            var preco = parseFloat(document.getElementById("preco_produto").value);
            if (!isNaN(quantidade) && !isNaN(preco)) {
                var total = quantidade * preco;
                document.getElementById("total-perdido").textContent = 'R$ ' + total.toFixed(2);
            } else {
                document.getElementById("total-perdido").textContent = 'R$ 0,00';
            }
        }
    </script>
</body>
</html>