<?php
// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "paracatu";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Obter os valores do formulário
$login = $_POST['login'];
$senha = $_POST['senha'];

// Gerar o hash da senha
$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

// Inserir o usuário no banco de dados
$sql = "INSERT INTO usuario (login, senha) VALUES ('$login', '$senhaHash')";

if ($conn->query($sql) === TRUE) {
    echo "Usuário cadastrado com sucesso!";
} else {
    echo "Erro ao cadastrar usuário: " . $conn->error;
}

// Fechar a conexão com o banco de dados
$conn->close();
?>
