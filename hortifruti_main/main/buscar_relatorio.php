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