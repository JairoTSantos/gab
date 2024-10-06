<?php

require_once dirname(__DIR__) . '/public/includes/Layout.php';
$layoutClass = new Layout();

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <?php $layoutClass->MontarHead('Cadastro'); ?>
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
        input[type="password"],
        input[type="text"],
        input[type="date"] {
            border-radius: 20px;
            /* Aqui você define o nível de arredondamento */
            padding: 15px;
            padding-left: 25px;
            margin-bottom: 0;
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

        a[type="button"] {
            background-color: #29ad24;
            color: white;
            border: none;
            border-radius: 20px;
            /* Aqui você define o nível de arredondamento */
            width: 100%;
            margin-left: 5px;
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
            margin-bottom: 5px;
            font-weight: 300;
        }

        .host {
            color: white;
            margin-bottom: 30px;
            font-weight: 300;
            font-size: 0.9em;
        }

        .copyright {
            color: #e3e3e8;
            font-size: 0.8em;
            font-weight: 100;
        }
    </style>
</head>

<body>

    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="centralizada text-center">

        <img src="img/logo_white.png" alt="" class="img_logo" />
            <h2 class="login_title">Gabinete Digital</h2>
            <h6 class="host"><?php echo $_SERVER['HTTP_HOST'] ?></h6>

            <?php

            session_start();
            $_SESSION['usuario_nivel'] = 1;

            require_once dirname(__DIR__) . '/app/controllers/UsuarioController.php';

            $usuarioController = new UsuarioController();

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_login'])) {

                $usuario_nome = isset($_POST['usuario_nome']) ? htmlspecialchars(trim($_POST['usuario_nome'])) : '';
                $usuario_email = isset($_POST['usuario_email']) ? filter_var(trim($_POST['usuario_email']), FILTER_VALIDATE_EMAIL) : '';
                $usuario_telefone = isset($_POST['usuario_telefone']) ? preg_replace('/[^0-9]/', '', trim($_POST['usuario_telefone'])) : '';
                $usuario_aniversario = isset($_POST['usuario_aniversario']) ? trim($_POST['usuario_aniversario']) : '';
                $usuario_ativo = 0;
                $usuario_nivel = 2;
                $usuario_senha = isset($_POST['usuario_senha']) ? htmlspecialchars(trim($_POST['usuario_senha'])) : '';
                $usuario_senha2 = isset($_POST['usuario_senha2']) ? htmlspecialchars(trim($_POST['usuario_senha2'])) : '';

                if ($usuario_senha !== $usuario_senha2) {
                    $layoutClass->alert('danger', 'As senhas não conferem.', 3, true);
                } elseif (strlen($usuario_senha) < 6) {
                    $layoutClass->alert('danger', 'A senha deve ter pelo menos 6 caracteres.', 3, true);
                } else {
                    $usuario = [
                        'usuario_nome' => $usuario_nome,
                        'usuario_email' => $usuario_email,
                        'usuario_telefone' => $usuario_telefone,
                        'usuario_aniversario' => $usuario_aniversario,
                        'usuario_ativo' => $usuario_ativo,
                        'usuario_nivel' => $usuario_nivel,
                        'usuario_senha' => $usuario_senha,
                        'foto' => null
                    ];
                    $resultado = $usuarioController->novoUsuario($usuario);

                    if ($resultado['status'] === 'success') {
                        $layoutClass->alert('success', 'Sua conta foi adicionada.<br> Aguarde a ativação.', 0, true);
                    } elseif ($resultado['status'] === 'duplicated' || $resultado['status'] === 'invalid_email' || $resultado['status'] === 'bad_request') {
                        $layoutClass->alert('info', $resultado['message'], 3, true);
                    } elseif ($resultado['status'] === 'error') {
                        $layoutClass->alert('danger', $resultado['message'], 3, true);
                    }
                }
                session_destroy();
            }

            ?>
            <form class="row g-2 form_custom" id="form_novo" method="POST" enctype="multipart/form-data">
                <div class="col-md-12 col-12">
                    <input type="text" class="form-control form-control-sm" name="usuario_nome" id="nome" placeholder="Nome" required>
                </div>
                <div class="col-md-12 col-12">
                    <input type="email" class="form-control form-control-sm" name="usuario_email" id="email" placeholder="Email" required>
                </div>
                <div class="col-md-6 col-6">
                    <input type="text" class="form-control form-control-sm" name="usuario_telefone" id="telefone" placeholder="Celular (com DDD)" maxlength="11" required>
                </div>
                <div class="col-md-6 col-6">
                    <input type="date" class="form-control form-control-sm" name="usuario_aniversario" id="aniversario" required>
                </div>
                <div class="col-md-6 col-6">
                    <input type="password" class="form-control form-control-sm" name="usuario_senha" placeholder="Senha" id="senha" required>
                </div>
                <div class="col-md-6 col-6">
                    <input type="password" class="form-control form-control-sm" name="usuario_senha2" placeholder="Confirme a senha" id="senha2" required>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <button type="submit" name="btn_login" class="btn btn-primary">Salvar</button>
                    <a type="button" href="login.php" class="btn btn-secondary">Voltar</a>

                </div>
            </form>

            <p class="mt-3 copyright">2024 | JS Digital System</p>

        </div>
    </div>
</body>

</html>