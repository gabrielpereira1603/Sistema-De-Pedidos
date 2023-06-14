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

// Consultar os pedidos com status aberto na tabela "pedido"
$sqlPedidos = "SELECT * FROM pedido WHERE status = 'aberto'";
$resultPedidos = $conn->query($sqlPedidos);

// Inicializar a lista de pedidos exibidos
$displayedOrders = array();

$newOrdersHTML = '';
if ($resultPedidos->num_rows > 0) {
    while ($rowPedido = $resultPedidos->fetch_assoc()) {
        $codPedido = $rowPedido['codpedido'];

        // Verificar se o pedido já foi exibido anteriormente
        if (in_array($codPedido, $displayedOrders)) {
            // Pular para o próximo loop
            continue;
        } else {
            // Adicionar o código do pedido à lista de pedidos exibidos
            $displayedOrders[] = $codPedido;
        }

        // Consultar os detalhes do pedido na tabela "detalhe_pedido"
        $sqlDetalhes = "SELECT m.codmarmita, t.valortamanho, m.observacao, p.codpedido
                        FROM marmita m
                        INNER JOIN tamanho t ON m.codtamanho = t.codtamanho
                        INNER JOIN pedido p ON m.codpedido = p.codpedido
                        WHERE m.codpedido = '$codPedido'
                        ORDER BY FIELD(t.valortamanho, 'P', 'M', 'G')";
        $resultDetalhes = $conn->query($sqlDetalhes);

        $newOrdersHTML .= '
        <div class="table-pedido">
            <table class="table">
                <thead>
                    <div class="title">
                        <h2> Pedido ' . $codPedido . '</h2>
                    </div>
                    <tr>
                        <th scope="col">Tamanho</th>
                        <th scope="col">Observação</th>
                    </tr>
                </thead>
                <tbody>';

        while ($rowDetalhe = $resultDetalhes->fetch_assoc()) {
            $tamanho = $rowDetalhe['valortamanho'];
            $observacao = $rowDetalhe['observacao'];

            $newOrdersHTML .= '
                <tr>
                    <td class="table-danger">' . $tamanho . '</td>
                    <td>' . $observacao . '</td>
                </tr>';
        }

        $newOrdersHTML .= '
            </tbody>
        </table>
        <button type="button" class="btn-finalizar-pedido" data-codpedido="' . $codPedido . '">Finalizar Pedido</button>
        </div>';
    }
} else {
    $newOrdersHTML .= "<div id='pedidos-container'>Não há pedidos abertos.</div>";
}

// Fechar a conexão com o banco de dados
$conn->close();

// Retornar o HTML para os novos pedidos
echo $newOrdersHTML;
?>
