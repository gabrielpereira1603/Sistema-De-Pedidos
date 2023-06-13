<?php
// Conexão com o banco de dados (substitua pelas suas configurações)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "paracatu";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);
// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Verificar se os dados das marmitas foram recebidos
if (isset($_POST["marmitas"]) && is_array($_POST["marmitas"])) {
    $marmitas = $_POST["marmitas"];

    // Iniciar a transação
    $conn->begin_transaction();

    try {
        // Inserir o pedido na tabela "pedido"
        $sqlPedido = "INSERT INTO pedido (status) VALUES ('aberto')";

        if ($conn->query($sqlPedido) !== TRUE) {
            throw new Exception("Ocorreu um erro ao inserir o pedido: " . $conn->error);
        }

        $pedidoId = $conn->insert_id;

        foreach ($marmitas as $marmita) {
            $observacao = $marmita["observacao"];
            $tamanho = $marmita["tamanho"];
        
            // Verificar se o tamanho não está vazio (seleção "Selecione um tamanho")
            if (!empty($tamanho)) {
                // Obter o valor selecionado no campo select do tamanho
                $tamanho = intval($tamanho); // Converter para inteiro
        
                // Inserir a marmita na tabela "marmita"
                $sqlMarmita = "INSERT INTO marmita (observacao, codtamanho, codpedido) VALUES ('$observacao', $tamanho, $pedidoId)";
        
                if ($conn->query($sqlMarmita) !== TRUE) {
                    throw new Exception("Ocorreu um erro ao inserir a marmita: " . $conn->error);
                }
            }
        }

        // Confirmar a transação
        $conn->commit();

        echo "success";
    } catch (Exception $e) {
        // Reverter a transação em caso de erro
        $conn->rollback();
        echo "Ocorreu um erro durante o processamento do pedido: " . $e->getMessage();
    }
} else {
    echo "error";
}

// Fechar a conexão com o banco de dados
$conn->close();
?>
