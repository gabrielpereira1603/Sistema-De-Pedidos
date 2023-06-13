<?php
session_start();

// Conexão com o banco de dados (substitua pelas suas configurações)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "paracatu";

// Verificar se o código do pedido foi enviado via solicitação POST
if (isset($_POST['codpedido'])) {
    $codPedido = $_POST['codpedido'];

    // Criar conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar conexão
    if ($conn->connect_error) {
        die("Falha na conexão com o banco de dados: " . $conn->connect_error);
    }

    // Atualizar o status do pedido para "fechado"
    $sqlAtualizarStatus = "UPDATE pedido SET status = 'fechado' WHERE codpedido = '$codPedido'";
    if ($conn->query($sqlAtualizarStatus) === TRUE) {
        // Atualização do status do pedido bem-sucedida
        $_SESSION['success_message'] = "Status do pedido atualizado para fechado.";
    } else {
        // Erro ao atualizar o status do pedido
        $_SESSION['error_message'] = "Erro ao atualizar o status do pedido: " . $conn->error;
    }

    // Fechar a conexão com o banco de dados
    $conn->close();
} else {
    // Código do pedido não foi fornecido
    $_SESSION['error_message'] = 'Código do pedido não fornecido.';
}

// Redirecionar para a página de consultar pedido
header("Location: consultar-pedido.php");
exit();
?>
