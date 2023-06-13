<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <title>Somos Dev's Sistemas</title>
</head>
<body style="background-color: #6AD4D9;">

    <div class="section-form">

        <form action="validar-login.php" method="POST" class="form">

            <div class="title">
                <h2>Iniciar Sessão</h2>
            </div>

            <div class="login">
                <label for="">Login:</label>
                <input type="text" name="login" placeholder="login"> 
            </div>
    
            <div class="login">
                <label for="">Senha:</label>
                <input type="number" name="senha" placeholder="senha"> 
            </div>
            

            <div class="d-grid gap-2 col-12 mx-auto">
                <button class="btn btn-primary" type="submit">Entrar</button>
            </div>
    
        </form>
    </div>

</body>
</html>