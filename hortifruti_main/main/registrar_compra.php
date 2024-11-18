<?php
session_start();

// Configuração da conexão com o banco de dados
$host = "localhost";
$username = "root";
$password = ""; // Deixe vazio, a menos que tenha definido uma senha
$dbname = "hortfrut"; // Confirme que o banco de dados existe

$mysqli = new mysqli($host, $username, $password, $dbname);

// Verificar a conexão
if ($mysqli->connect_error) {
    die("Conexão falhou: " . $mysqli->connect_error);
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipo = $_POST['TIPO'];
    $valor = $_POST['VALOR'];
    $nome = $_POST['NOME'];
    $numero_nota_fiscal = $_POST['NUMERO_NOTA_FISCAL'];
    $data_compra = $_POST['DATA_COMPRA'];
    $preco_total = $_POST['PRECO_TOTAL'];
    $preco_compra = $_POST['PRECO_COMPRA'];
    $preco_venda = $_POST['PRECO_VENDA'];

    // Definir os valores para KG ou QUANTIDADE com base no tipo selecionado
    $kg = ($tipo === "KG") ? $valor : 0;
    $quantidade = ($tipo === "UNIDADE") ? $valor : 0;

    // Inserir os dados no banco de dados
    $sql = "INSERT INTO compra (QUANTIDADE, NOME, NUMERO_NOTA_FISCAL, DATA_COMPRA, PRECO_TOTAL, KG, PRECO_COMPRA, PRECO_VENDA) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param(
            "issddddd",
            $quantidade,
            $nome,
            $numero_nota_fiscal,
            $data_compra,
            $preco_total,
            $kg,
            $preco_compra,
            $preco_venda
        );

        if ($stmt->execute()) {
            echo "<p style='color:green;'>Compra registrada com sucesso!</p>";
        } else {
            echo "<p style='color:red;'>Erro ao registrar compra: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p style='color:red;'>Erro na preparação da consulta: " . $mysqli->error . "</p>";
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Compra</title>
    <script>
        function toggleFields() {
            const tipo = document.getElementById("tipo").value;
            const valorInput = document.getElementById("valor");

            if (tipo === "KG") {
                valorInput.placeholder = "Insira o peso em KG";
                valorInput.disabled = false;
            } else if (tipo === "UNIDADE") {
                valorInput.placeholder = "Insira a quantidade";
                valorInput.disabled = false;
            } else {
                valorInput.placeholder = "";
                valorInput.disabled = true;
            }
        }
    </script>
</head>
<body>
    <h1>Registro de Compra</h1>
    <form action="" method="POST">
        <label for="tipo">Selecione o Tipo:</label>
        <select id="tipo" name="TIPO" onchange="toggleFields()" required>
            <option value="">Selecione...</option>
            <option value="KG">KG</option>
            <option value="UNIDADE">Unidade</option>
        </select>

        <br><br>
        <label for="valor">Valor:</label>
        <input type="number" step="0.01" id="valor" name="VALOR" placeholder="Escolha o tipo primeiro" disabled required>

        <br><br>
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="NOME" required>

        <br><br>
        <label for="numero_nota_fiscal">Número Nota Fiscal:</label>
        <input type="text" id="numero_nota_fiscal" name="NUMERO_NOTA_FISCAL" required>

        <br><br>
        <label for="data_compra">Data de Compra:</label>
        <input type="date" id="data_compra" name="DATA_COMPRA" required>

        <br><br>
        <label for="preco_total">Preço Total:</label>
        <input type="number" step="0.01" id="preco_total" name="PRECO_TOTAL" required>

        <br><br>
        <label for="preco_compra">Preço de Compra:</label>
        <input type="number" step="0.01" id="preco_compra" name="PRECO_COMPRA" required>

        <br><br>
        <label for="preco_venda">Preço de Venda:</label>
        <input type="number" step="0.01" id="preco_venda" name="PRECO_VENDA" required>

        <br><br>
        <button type="submit">Registrar Compra</button>
    </form>
</body>
</html>
