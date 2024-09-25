<?php

require_once dirname(__DIR__) . '/public/includes/Layout.php';
$layoutClass = new Layout();

require_once dirname(__DIR__) . '/app/core/Login.php';
$login = new Login();

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <?php $layoutClass->MontarHead('Login'); ?>
    <style>
        /* Definindo a altura mínima da tela */
        html,
        body {
            height: 100%;
        }

        .centralizada {
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        /* Estilo customizado para arredondar os inputs e o botão */
        input[type="email"],
        input[type="password"] {
            border-radius: 20px;
            /* Aqui você define o nível de arredondamento */
            padding: 15px;
            padding-left: 25px;
            border: 1px solid #ccc;
            color: gray;
            font-size: 1em;
            font-weight: 500;
        }

        button[type="submit"] {
            background-color: #272775;
            color: white;
            border: none;
            border-radius: 20px;
            /* Aqui você define o nível de arredondamento */
            width: 100%;
            margin-top: 10px;
            padding: 10px;
            font-size: 1.2em;
            font-weight: 500;
        }


        button[type="submit"]:hover {
            background-color: green;
            color: white;
        }

        /* Adicionando espaçamento interno e margem */
        .form-control {
            margin-bottom: 10px;
        }

        .link {
            color: white;
            font-weight: 200;
        }

        a {
            text-decoration: none;
            color: white;
        }

        .img_logo {
            width: 30%;
            margin-bottom: 30px;
        }

        .login_title {
            color: white;
            margin-bottom: 30px;
            font-weight: 300;
        }
    </style>
</head>

<body>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="centralizada text-center">
            <img src="img/logo_white.png" alt="" class="img_logo" />
            <h2 class="login_title">Gabinete Digital</h2>
            <?php

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_login'])) {
                $email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL) : '';
                $senha = isset($_POST['senha']) ? trim($_POST['senha']) : '';

                $dados = [
                    'email' => $email,
                    'senha' => $senha
                ];

                $resultado = $login->Logar($dados);

                if ($resultado['status'] === 'not_found' || $resultado['status'] === 'deactivated') {
                    $layoutClass->alert('info', $resultado['message'], 3, true);
                } else if ($resultado['status'] === 'error' || $resultado['status'] === 'wrong_password') {
                    $layoutClass->alert('danger', $resultado['message'], 3, true);
                } else {
                    header('Location: home.php');
                }
            }

            ?>
            <form id="form_login" method="post" enctype="application/x-www-form-urlencoded" class="form-group">
                <div class="form-group">
                    <input type="email" class="form-control " name="email" id="email" placeholder="E-mail" value="jairojeffersont@gmail.com2" required>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control " name="senha" id="senha" placeholder="Senha" value="intell01" required>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <button type="submit" name="btn_login" class="btn ">Entrar</button>
                </div>
            </form>
            <p class="mt-3 link">Esqueceu a senha? | <a href="cadastro.php">Faça seu cadastro</a></p>
        </div>
    </div>
</body>

</html>