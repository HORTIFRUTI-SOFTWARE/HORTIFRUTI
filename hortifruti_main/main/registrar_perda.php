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
    <title>Registrar PERCA</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 10px 0;
            text-align: center;
        }
        form {
            max-width: 500px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background: #f9f9f9;
        }
        label, input, select, button {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }
        button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        .produto-lista {
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ccc;
            margin-top: 10px;
        }
        .produto-lista div {
            padding: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header>
        <h1>Registrar PERCA</h1>
        <nav>
            <a href="dashboard.php" style="color: white;">Voltar ao Início</a>
        </nav>
    </header>

    <section id="formulario">
    <form method="POST" action="">
        <label for="produto">Pesquisar Produto</label>
        <input type="text" id="produto" name="produto" onkeyup="buscarProduto()" placeholder="Pesquise pelo nome do produto...">

        <div id="produto-lista" class="produto-lista">
            <?php foreach ($produtos as $produto): ?>
                <div onclick="selecionarProduto(<?= $produto['COD_PRODUTO'] ?>, '<?= addslashes($produto['nome']) ?>')">
                    <?= $produto['nome'] ?>
                </div>
            <?php endforeach; ?>
        </div>

        <input type="hidden" id="id_produto" name="COD_PRODUTO">

        <label for="quantidade_perdida">Quantidade Perdida (kg)</label>
        <input type="number" name="QUANTIDADE" id="quantidade_perdida" step="0.01" required>

        <button type="submit">Registrar PERCA</button>
    </form>

    <p style="text-align: center; color: <?= strpos($mensagem, 'sucesso') !== false ? 'green' : 'red' ?>;">
        <?= $mensagem ?>
    </p>
</section>

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
        document.getElementById("produto-lista").innerHTML = '';  // Limpar a lista de sugestões
        }
    </script>
</body>
</html>