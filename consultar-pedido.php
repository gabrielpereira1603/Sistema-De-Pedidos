<?php
// Define o tempo limite de inatividade da sessão em 30 minutos
$session_timeout = 1800; // 1800 30 minutos em segundos
session_set_cookie_params($session_timeout);

// Inicia a sessão
session_start();

// Verifica se a variável de sessão 'autenticado' está definida
if (!isset($_SESSION['autenticado']) || !$_SESSION['autenticado']) {
    // Redireciona o usuário de volta para a página de login
    header("Location: login.php");
    exit;
}

// Verifica se o tempo de inatividade da sessão expirou
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $session_timeout)) {
    // Destroi a sessão atual
    session_unset();
    session_destroy();
    // Redireciona o usuário de volta para a página de login
    header("Location: login.php");
    exit;
}

// Atualiza o último tempo de atividade da sessão
$_SESSION['last_activity'] = time();

// Continua a execução da página normalmente
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="stylesheet" href="assets/css/consultar.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <title>Consultar Pedidos</title>
    </head>

    <body>
        <!-- Exibe a mensagem de erro de alteracao de senha-->
        <?php if(isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger">
                <?php 
                    echo $_SESSION['error_message']; 
                    unset($_SESSION['error_message']); 
                ?>
            </div>
        <?php endif; ?>

        <!-- Exibe a mensagem de sucesso de alteracao de senha -->
        <?php if(isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?php 
                    echo $_SESSION['success_message']; 
                    unset($_SESSION['success_message']); 
                ?>
            </div>
        <?php endif; ?>

        <?php
            include("cabecario.php");
        ?>


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

            ?>

            <?php
            if ($resultPedidos->num_rows > 0) {
                while ($rowPedido = $resultPedidos->fetch_assoc()) {
                    $codPedido = $rowPedido['codpedido'];

                    // Consultar os detalhes do pedido na tabela "detalhe_pedido"
                    $sqlDetalhes = "SELECT m.codmarmita, t.valortamanho, m.observacao, p.codpedido
                                    FROM marmita m
                                    INNER JOIN tamanho t ON m.codtamanho = t.codtamanho
                                    INNER JOIN pedido p ON m.codpedido = p.codpedido
                                    WHERE m.codpedido = '$codPedido'
                                    ORDER BY FIELD(t.valortamanho, 'P', 'M', 'G')";
                    $resultDetalhes = $conn->query($sqlDetalhes);
                    ?>

                    <div class="table-pedido">
                        <table class="table">
                            <thead>

                                <div class="title">
                                    <h2> Pedido <?php echo $codPedido?></h2>
                                </div>

                                <tr>
                                    <th scope="col">Tamanho</th>
                                    <th scope="col">Observação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($rowDetalhe = $resultDetalhes->fetch_assoc()) {
                                    $tamanho = $rowDetalhe['valortamanho'];
                                    $observacao = $rowDetalhe['observacao'];
                                    ?>

                                    <tr>
                                        <td class="table-danger"><?php echo $tamanho; ?></td>
                                        <td><?php echo $observacao; ?></td>
                                    </tr>

                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>

                        <button type="button" class="btn-finalizar-pedido" data-codpedido="<?php echo $codPedido; ?>">Finalizar Pedido</button>
                    </div>
                    <?php
                }
            } else {
                echo "Não há pedidos abertos.";
            }
        ?>

        <?php
        // Fechar a conexão com o banco de dados
        $conn->close();
        ?>


        <script>
            // Função para recarregar a página após um determinado tempo (500 milissegundos neste exemplo)
            function reloadPageWithMessage() {
                setTimeout(function() {
                    location.reload();
                }, 500);
            }

            // Capturar o evento de clique do botão "Finalizar Pedido"
            document.addEventListener('click', function(event) {
                if (event.target && event.target.classList.contains('btn-finalizar-pedido')) {
                    var codPedido = event.target.getAttribute('data-codpedido');

                    // Fazer uma solicitação AJAX para atualizar o status do pedido
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'atualizar-status-pedido.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                            // Atualização do status do pedido bem-sucedida
                            // Recarregar a página com mensagem de sucesso
                            console.log('Status do pedido atualizado para fechado.');
                            reloadPageWithMessage();
                        }
                    };
                    xhr.send('codpedido=' + codPedido);
                }
            });
        </script>

        <?php
        include("footer.php");
        ?>
    </body>
</html>