<?php
session_start();
session_destroy(); // encerra a sessão do usuário
header("Location: login.php"); // redireciona para a página de login
exit;
?>