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

// Consultar os tamanhos das marmitas na tabela "tamanho"
$sqlTamanhos = "SELECT * FROM tamanho";
$resultTamanhos = $conn->query($sqlTamanhos);

$tamanhos = array();

if ($resultTamanhos->num_rows > 0) {
    while ($row = $resultTamanhos->fetch_assoc()) {
        $tamanho = array(
            'codtamanho' => $row['codtamanho'],
            'valortamanho' => $row['valortamanho']
        );
        $tamanhos[] = $tamanho;
    }
}

// Retornar os tamanhos como resposta no formato JSON
header('Content-Type: application/json');
echo json_encode($tamanhos);

// Fechar a conexão com o banco de dados
$conn->close();
?>
