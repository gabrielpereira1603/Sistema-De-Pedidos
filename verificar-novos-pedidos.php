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

// Consultar se há novos pedidos abertos na tabela "pedido"
$sql = "SELECT COUNT(*) AS total_pedidos FROM pedido WHERE status = 'aberto'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$totalPedidos = $row['total_pedidos'];

// Fechar a conexão com o banco de dados
$conn->close();

// Retornar 'true' se houver novos pedidos ou 'false' caso contrário
echo ($totalPedidos > 0) ? 'true' : 'false';
?>
