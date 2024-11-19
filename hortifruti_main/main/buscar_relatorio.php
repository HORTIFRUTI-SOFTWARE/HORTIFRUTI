<?php
    // Verificar se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data_inicio = $_POST['data_inicio'];
        $data_fim = $_POST['data_fim'];
        
        // Conectar ao banco de dados
        $mysqli = new mysqli("localhost", "root", "", "hortfrut");
        
        if ($mysqli->connect_error) {
            die("Erro na conexão com o banco de dados: " . $mysqli->connect_error);
        }

        // Consultar as vendas no período
        $vendas_query = "SELECT SUM(valor_venda) AS total_vendas FROM vendas WHERE data_venda BETWEEN ? AND ?";
        $stmt = $mysqli->prepare($vendas_query);
        $stmt->bind_param("ss", $data_inicio, $data_fim);
        $stmt->execute();
        $stmt->bind_result($total_vendas);
        $stmt->fetch();
        $stmt->close();
        
        // Consultar as compras no período
        $compras_query = "SELECT SUM(valor_compra) AS total_compras FROM compras WHERE data_compra BETWEEN ? AND ?";
        $stmt = $mysqli->prepare($compras_query);
        $stmt->bind_param("ss", $data_inicio, $data_fim);
        $stmt->execute();
        $stmt->bind_result($total_compras);
        $stmt->fetch();
        $stmt->close();
        
        // Consultar as perdas no período
        $percas_query = "SELECT SUM(valor_perca) AS total_percas FROM percas WHERE data_perca BETWEEN ? AND ?";
        $stmt = $mysqli->prepare($percas_query);
        $stmt->bind_param("ss", $data_inicio, $data_fim);
        $stmt->execute();
        $stmt->bind_result($total_percas);
        $stmt->fetch();
        $stmt->close();
        
        // Exibir os resultados
        echo "<h2>Relatório de " . date('d/m/Y', strtotime($data_inicio)) . " até " . date('d/m/Y', strtotime($data_fim)) . "</h2>";
        echo "<p><strong>Total de Vendas: </strong>R$ " . number_format($total_vendas, 2, ',', '.') . "</p>";
        echo "<p><strong>Total de Compras: </strong>R$ " . number_format($total_compras, 2, ',', '.') . "</p>";
        echo "<p><strong>Total de Perdas: </strong>R$ " . number_format($total_percas, 2, ',', '.') . "</p>";

        // Fechar a conexão com o banco de dados
        $mysqli->close();
    }
    ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Vendas, Compras e Perdas</title>
    <link rel="stylesheet" href="..\src\css\relat.css">
</head>
<body>
    <div class="container">
        <h1>Relatório de Vendas, Compras e Perdas</h1>

        <!-- Formulário para seleção de datas -->
        <form action="../main/buscar_relatorio.php" method="POST">
            <label for="data_inicio">Data de Início:</label>
            <input type="date" id="data_inicio" name="data_inicio" required>

            <label for="data_fim">Data de Fim:</label>
            <input type="date" id="data_fim" name="data_fim" required>

            <input type="submit" value="Gerar Relatório">
        </form>
        <a href="dashboard.php" class="btn-voltar">Voltar ao Início</a>
        <!-- Exibição dos resultados (adicionada uma seção de resultado estilizada) -->
        <div class="resultado">
            <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    echo "<h2>Relatório de " . date('d/m/Y', strtotime($data_inicio)) . " até " . date('d/m/Y', strtotime($data_fim)) . "</h2>";
                    echo "<p><strong>Total de Vendas: </strong>R$ " . number_format($total_vendas, 2, ',', '.') . "</p>";
                    echo "<p><strong>Total de Compras: </strong>R$ " . number_format($total_compras, 2, ',', '.') . "</p>";
                    echo "<p><strong>Total de Perdas: </strong>R$ " . number_format($total_percas, 2, ',', '.') . "</p>";
                }
            ?>
        </div>
    </div>
</body>
</html>