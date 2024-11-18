<?php
session_start();
include('config.php'); // Conexão com o banco de dados

// Variáveis de mensagem
$mensagem = "";

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
                $updateQuery = "UPDATE produto SET quantidade = quantidade + ? WHERE COD_PRODUTO = ?";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bind_param('di', $quantidade_perdida, $id_produto);
                if ($updateStmt->execute()) {
                    $mensagem = "Inclusão registrada com sucesso!";
                } else {
                    $mensagem = "Erro ao atualizar o estoque: " . $conn->error;
                }
            } else {
                $mensagem = "Erro ao registrar a Inclusão: " . $conn->error;
            }
        } else {
            $mensagem = "Por favor, preencha a quantidade inclusa corretamente.";
        }
    } else {
        $mensagem = "Erro: Dados de produto ou quantidade não enviados.";
    }
}

// Pesquisa de produtos
$produtos = [];
if (isset($_GET['query']) && !empty($_GET['query'])) {
    $searchQuery = '%' . $_GET['query'] . '%';
    $stmtSearch = $conn->prepare("SELECT COD_PRODUTO, nome FROM produto WHERE nome LIKE ?");
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
    <title>Incluir Quantidade</title>
    <link rel="stylesheet" href="..\src\css\adc_qt.css">
</head>
<body>
<div class="form-container">
    <div class="form-card1">
        <div class="form-card2">
            <form method="POST" action="" class="form">
                <p class="form-heading">Incluir Quantidade</p>

                <div class="form-field">
                    <input 
                        type="text" 
                        id="produto" 
                        name="produto" 
                        onkeyup="buscarProduto()" 
                        placeholder="Pesquise pelo nome do produto..."
                        class="input-field"
                    />
                </div>

                <div id="produto-lista" class="produto-lista">
                    <?php foreach ($produtos as $produto): ?>
                        <div 
                            onclick="selecionarProduto(<?= $produto['COD_PRODUTO'] ?>, '<?= addslashes($produto['nome']) ?>')"
                        >
                            <?= $produto['nome'] ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <input type="hidden" id="id_produto" name="COD_PRODUTO">

                <div class="form-field">
                    <input 
                        type="number" 
                        name="QUANTIDADE" 
                        id="quantidade_perdida" 
                        step="0.01" 
                        placeholder="Quantidade Incluída (kg)" 
                        required
                        class="input-field"
                    />
                </div>

                <button type="submit" class="sendMessage-btn">Registrar Inclusão</button>
                <a class="voltar_btn" href="dashboard.php" id="logout_btn" style="text-decoration: none;">
        <i class="fa-solid fa-right-from-bracket"></i>
        <span class="item-description">Voltar</span>
    </a>
            </form>


            <p class="message <?= strpos($mensagem, 'sucesso') !== false ? 'success' : (strpos($mensagem, 'Erro') !== false ? 'error' : '') ?>">
                <?= $mensagem ?>
            </p>
        </div>
    </div>
</div>

<script>
    function buscarProduto() {
        var query = document.getElementById("produto").value;
        if (query.length > 2) {
            window.location.href = "?query=" + query;
        }
    }

    function selecionarProduto(id, nome) {
        document.getElementById("produto").value = nome;
        document.getElementById("id_produto").value = id;
        document.getElementById("produto-lista").innerHTML = ''; // Limpar a lista de sugestões
    }
</script>
</body>
</html>
