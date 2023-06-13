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
include("conexao.php");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link rel="stylesheet" href="assets/css/cadastro.css">

        <title>Cadastrar Pedidos</title>
    </head>
    <body>
        <?php
            include("cabecario.php");
        ?>
        
        <section class="main-content">
            <div class="title">
                <h1>Cadastrar Pedidos</h1>
            </div>

            <div class="section-pedido">
                <div class="mb-3">
                <label for="quantidadeMarmitas" class="form-label">Selecione a Quantidade de Marmitas:</label>
                <input type="number" class="form-control" id="quantidadeMarmitas" placeholder="1,2,3,4">
                </div>
                <div class="d-grid gap-2 col-12 mx-auto">
                <button class="btn btn-primary" type="button" id="selecionarBtn">Selecionar</button>
                </div>
            </div>

            <form method="post" class="form">
                <div class="d-grid gap-2 col-12 mx-auto" id="marmitasContainer">
                <!-- As divs de marmitas serão adicionadas aqui dinamicamente -->
                </div>

                <div class="d-grid gap-2 col-12 mx-auto">
                <button class="btn btn-primary" type="submit">Fazer Pedido</button>
                </div>
            </form>
        </section>

        <script>
    document.addEventListener("DOMContentLoaded", function () {
        let selecionarBtn = document.getElementById("selecionarBtn");
        let form = document.querySelector(".form");

        selecionarBtn.addEventListener("click", function () {
            let quantidadeMarmitasInput = document.querySelector("#quantidadeMarmitas");
            let quantidadeMarmitas = parseInt(quantidadeMarmitasInput.value);

            if (!isNaN(quantidadeMarmitas) && quantidadeMarmitas > 0) {
                // Remove as divs de marmita existentes antes de gerar as novas
                let divsMarmitas = document.querySelectorAll(".div-marmita");
                divsMarmitas.forEach(function (div) {
                    div.remove();
                });

                for (let i = 1; i <= quantidadeMarmitas; i++) {
                    let divMb3 = document.createElement("div");
                    divMb3.classList.add("div-marmita", "mb-3");

                    let label = document.createElement("label");
                    label.setAttribute("for", "marmita-" + i);
                    label.classList.add("form-label");
                    label.textContent = "Marmita " + i + ":";

                    let inputObservacao = document.createElement("input");
                    inputObservacao.setAttribute("type", "text");
                    inputObservacao.setAttribute("name", "observacao");
                    inputObservacao.classList.add("form-control");
                    inputObservacao.setAttribute("id", "marmita-" + i);
                    inputObservacao.setAttribute("placeholder", "Observação");

                    let selectTamanho = document.createElement("select");
                    selectTamanho.setAttribute("name", "tamanho[]"); // Alterado para receber um array de valores
                    selectTamanho.classList.add("form-control");
                    selectTamanho.setAttribute("id", "tamanho-" + i);

                    // Crie uma opção vazia para permitir a seleção do valor
                    let optionVazio = document.createElement("option");
                    optionVazio.value = "";
                    optionVazio.textContent = "Selecione um tamanho";
                    selectTamanho.appendChild(optionVazio);

                    divMb3.appendChild(label);
                    divMb3.appendChild(inputObservacao);
                    divMb3.appendChild(selectTamanho);

                    form.insertBefore(divMb3, form.lastElementChild);

                   // Fazer uma solicitação AJAX para buscar os tamanhos disponíveis
                    $.ajax({
                        url: "buscar-tamanho.php",
                        type: "GET",
                        dataType: "json",
                        success: function (response) {
                            response.forEach(function (tamanho) {
                                let option = document.createElement("option");
                                option.value = tamanho.codtamanho; // Alterado para o valor do codtamanho
                                option.textContent = tamanho.valortamanho; // Alterado para o valor do valortamanho
                                selectTamanho.appendChild(option);
                            });
                        },
                        error: function () {
                            alert("Ocorreu um erro ao buscar os tamanhos. Por favor, tente novamente.");
                        }
                    });
                }
            } else {
                alert("Por favor, insira um número válido para a quantidade de marmitas.");
            }
        });
    });

    document.addEventListener("DOMContentLoaded", function () {
        let form = document.querySelector(".form");

        form.addEventListener("submit", function (event) {
            event.preventDefault(); // Impede o comportamento padrão de envio do formulário

            // Obter os valores das marmitas selecionadas
            let divsMarmitas = document.querySelectorAll(".div-marmita");
            let marmitas = [];

            divsMarmitas.forEach(function (div) {
                let observacaoInput = div.querySelector("input[name='observacao']");
                let tamanhoSelect = div.querySelector("select[name='tamanho[]']"); // Alterado para receber um array de valores

                let marmita = {
                    observacao: observacaoInput.value,
                    tamanho: tamanhoSelect.value
                };

                marmitas.push(marmita);
            });

            // Validar se foram selecionadas marmitas
            if (marmitas.length > 0) {
                // Realizar a solicitação AJAX para inserir o pedido no banco de dados
                $.ajax({
                    url: "inserir-pedido.php",
                    type: "POST",
                    data: { marmitas: marmitas },
                    success: function (response) {
                        console.log(response); // Exibir a resposta no console
                        // Limpar o formulário e exibir uma mensagem de sucesso
                        form.reset();
                        alert("Pedido realizado com sucesso!");
                    },
                    error: function (xhr, status, error) {
                        console.log(xhr.responseText); // Exibir a mensagem de erro no console
                        alert("Ocorreu um erro ao fazer o pedido. Por favor, tente novamente.");
                    }
                });
            } else {
                alert("Selecione pelo menos uma marmita para fazer o pedido.");
            }
        });
    });
</script>



        <?php
            include("footer.php");
        ?>

    </body>
</html>