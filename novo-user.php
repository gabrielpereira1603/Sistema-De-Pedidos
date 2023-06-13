<!DOCTYPE html>
<html>
<head>
    <title>Cadastro de Usuário</title>
</head>
<body>
    <h2>Cadastro de Usuário</h2>
    <form action="processar-cadastro.php" method="post">
        <label for="login">Login:</label>
        <input type="text" id="login" name="login" required>
        <br>
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required>
        <br>
        <input type="submit" value="Cadastrar">
    </form>
</body>
</html>
