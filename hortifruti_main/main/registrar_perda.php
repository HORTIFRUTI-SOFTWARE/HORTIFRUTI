<?php
session_start();
include('config.php'); // Conexão com o banco de dados

// Variáveis de mensagem
$mensagem = "";
$VALOR_TOTAL = 0; // Inicializa a variável

// Verificar se o usuário está logado
if (!isset($_SESSION['username'])) {
    header("Location: indexLogin.php");
    exit();
}

// Processar o formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['COD_PRODUTO']) && isset($_POST['QUANTIDADE'])) {
        $id_produto = $_POST['COD_PRODUTO'];           // ID do produto
        $quantidade_perdida = floatval($_POST['QUANTIDADE']); // Quantidade perdida

        // Verificar se os dados são válidos
        if ($quantidade_perdida > 0) {
            // Buscar o preço do produto selecionado
            $queryProduto = "SELECT preco, nome FROM produto WHERE COD_PRODUTO = ?";
            $stmtProduto = $conn->prepare($queryProduto);
            $stmtProduto->bind_param('i', $id_produto);
            $stmtProduto->execute();
            $stmtProduto->bind_result($preco, $nomeProduto);
            $stmtProduto->fetch();
            $stmtProduto->close();

            // Calcular o valor da PERCA
            $VALOR_TOTAL = $quantidade_perdida * $preco;

            // Registrar a PERCA na tabela de PERCA
            $query = "INSERT INTO PERCA (COD_PRODUTO, QUANTIDADE, VALOR_TOTAL, DATA_PERCA) 
                      VALUES (?, ?, ?, NOW())";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('idd', $id_produto, $quantidade_perdida, $VALOR_TOTAL);
            if ($stmt->execute()) {
                // Atualizar o estoque
                $updateQuery = "UPDATE produto SET quantidade = quantidade - ? WHERE COD_PRODUTO = ?";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bind_param('di', $quantidade_perdida, $id_produto);
                if ($updateStmt->execute()) {
                    $mensagem = "PERCA registrada com sucesso!";
                } else {
                    $mensagem = "Erro ao atualizar o estoque: " . $conn->error;
                }
            } else {
                $mensagem = "Erro ao registrar a PERCA: " . $conn->error;
            }
        } else {
            $mensagem = "Por favor, preencha a quantidade perdida corretamente.";
        }
    } else {
        $mensagem = "Erro: Dados de produto ou quantidade não enviados.";
    }
}

// Pesquisa de produtos
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
    <title>Registrar PERCA</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="..\src\css\perda.css">
</head>
<body>

    <header>
        <h1><i class="fas fa-box-open"></i> Registrar Perda de Produto</h1>
    </header>

    <div class="container">
        <form class="forme" method="POST" action="">
            <label for="produto">Pesquisar Produto</label>
            <input type="text" id="produto" name="produto" onkeyup="buscarProduto()" placeholder="Pesquise pelo nome do produto..." required>

            <div id="produto-lista" class="produto-lista">
                <?php foreach ($produtos as $produto): ?>
                    <div onclick="selecionarProduto(<?= $produto['COD_PRODUTO'] ?>, '<?= addslashes($produto['nome']) ?>', <?= $produto['preco'] ?>)">
                        <?= $produto['nome'] ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <input type="hidden" id="id_produto" name="COD_PRODUTO">
            <input type="hidden" id="preco_produto" value="0">

            <label for="quantidade_perdida">Quantidade Perdida (kg)</label>
            <input type="number" name="QUANTIDADE" id="quantidade_perdida" step="0.01" placeholder="Digite a quantidade perdida..." required oninput="calcularTotal()">

            <button type="submit"><i class="fas fa-save"></i> Registrar PERCA</button>
        </form>

        <?php if ($mensagem): ?>
            <div class="mensagem <?= strpos($mensagem, 'sucesso') !== false ? 'success' : 'error' ?>">
                <?= $mensagem ?>
            </div>
        <?php endif; ?>

        <div class="total-perda">
            <h3>Total Perdido</h3>
            <p id="total-perdido">R$ 0,00</p>
        </div>

        <div class="back-button">
            <a href="dashboard.php"><i class="fas fa-home"></i> Voltar à Página Inicial</a>
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
