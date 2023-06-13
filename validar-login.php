<?php
session_start();
include('conexao.php');

$senha = $_POST["senha"];            
$login = $_POST["login"];

// Verifica se houve POST e se o usuário ou a senha é(são) vazio(s)
if (!empty($_POST) AND (empty($_POST['login']) OR empty($_POST['senha']))) {
    $_SESSION['error'] = 'Login ou Senha não podem estar vazios!';
    header("Location: login.php"); 
    exit;
}

$sql = "SELECT codusuario, senha FROM usuario WHERE login = ?";

if ($stmt = mysqli_prepare($conexao, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $login);

    mysqli_stmt_execute($stmt);

    $resultado = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($resultado) == 1) {
        // Recupera os dados do aluno
        $dados_usuario = mysqli_fetch_assoc($resultado);
        
        // Check the password
        if (!password_verify($senha, $dados_usuario['senha'])) {
            $_SESSION['error'] = 'Senha incorreta!';
            header("Location: login.php");
            exit;
        }


        // Define a variável de sessão para indicar que o usuário está autenticado
        $_SESSION['autenticado'] = true;
        
        // Define a variável de sessão com o RM do aluno autenticado
        $_SESSION['login'] = $login;

        // Define a variável de sessão com o código do usuário do aluno autenticado
        $_SESSION['codusuario'] = $dados_usuario['codusuario'];

        header("Location: home.php");
    } else {
        $_SESSION['error'] = 'Login ou Senha incorretos!';
        header("Location: index-aluno.php");
    }
}
?>

